<?php
/**
 * =====================================================
 * FILE: api/get_products.php
 * Mục đích: API lấy danh sách sản phẩm động từ CSDL (bảng sanpham & danhmuc)
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../csdl/db.php';

try {
    $pdo = getDB();

    // 1. Kiểm tra nếu chưa có danh mục & sản phẩm nào trong DB -> Tự động khởi tạo hạt giống (Seed Data)
    $countStmt = $pdo->query("SELECT COUNT(*) FROM sanpham");
    $productCount = (int)$countStmt->fetchColumn();

    if ($productCount === 0) {
        seedInitialProducts($pdo);
    }

    // 2. Tiếp nhận tham số lọc từ URL (nếu có)
    $categorySlug = trim($_GET['category'] ?? '');
    $search = trim($_GET['search'] ?? '');
    $sort = trim($_GET['sort'] ?? 'newest');

    $sql = "
        SELECT 
            s.ID as id,
            s.TenSanPham as name,
            s.Slug as slug,
            s.GiaGoc as old_price,
            s.GiaBan as price,
            s.DonViTinh as unit,
            s.SoLuongTon as stock,
            s.HinhAnh as image,
            s.TieuChuan as standard,
            s.MoTa as description,
            s.TrangThai as status,
            d.TenDanhMuc as category,
            d.Slug as category_slug
        FROM sanpham s
        JOIN danhmuc d ON s.DanhMuc_id = d.ID
        WHERE s.TrangThai = 1
    ";

    $params = [];

    if (!empty($categorySlug) && $categorySlug !== 'all') {
        $sql .= " AND d.Slug = ?";
        $params[] = $categorySlug;
    }

    if (!empty($search)) {
        $sql .= " AND (s.TenSanPham LIKE ? OR s.MoTa LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }

    switch ($sort) {
        case 'price_asc':
            $sql .= " ORDER BY s.GiaBan ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY s.GiaBan DESC";
            break;
        case 'name_asc':
            $sql .= " ORDER BY s.TenSanPham ASC";
            break;
        case 'newest':
        default:
            $sql .= " ORDER BY s.ThoiGianTao DESC";
            break;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chuẩn hóa dữ liệu trả về cho Frontend
    $result = array_map(function ($p) {
        $price = (float)$p['price'];
        $oldPrice = (float)$p['old_price'];
        $discount = 0;
        if ($oldPrice > $price && $oldPrice > 0) {
            $discount = round((($oldPrice - $price) / $oldPrice) * 100);
        }

        return [
            'id'            => $p['id'],
            'name'          => $p['name'],
            'slug'          => $p['slug'],
            'price'         => $price,
            'old_price'     => $oldPrice,
            'discount_pct'  => $discount,
            'unit'          => $p['unit'] ?: '500g',
            'stock'         => (int)$p['stock'],
            'image'         => $p['image'] ?: 'img/ca-chua-bi.jpg',
            'standard'      => $p['standard'] ?: 'VietGAP',
            'category'      => $p['category'],
            'category_slug' => $p['category_slug'],
            'description'   => $p['description'],
            'rating'        => 5.0,
            'reviews'       => 48
        ];
    }, $products);

    echo json_encode([
        'success' => true,
        'count'   => count($result),
        'data'    => $result
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi truy vấn sản phẩm: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * Hàm khởi tạo hạt giống dữ liệu ban đầu cho FreshMart
 */
function seedInitialProducts($pdo) {
    try {
        // 1. Đảm bảo có User Admin / Owner
        $stmtUser = $pdo->query("SELECT ID FROM nguoidung LIMIT 1");
        $userId = $stmtUser->fetchColumn();
        if (!$userId) {
            $userId = generateUUID();
            $pdo->prepare("
                INSERT INTO nguoidung (ID, Email, MatKhauMaHoa, HoTen, VaiTro)
                VALUES (?, 'admin@freshmart.vn', ?, 'Ban Quản Trị FreshMart', 'OWNER')
            ")->execute([$userId, password_hash('FreshMart@2026', PASSWORD_DEFAULT)]);
        }

        // 2. Đảm bảo có Cửa hàng
        $stmtShop = $pdo->query("SELECT ID FROM cuahang LIMIT 1");
        $shopId = $stmtShop->fetchColumn();
        if (!$shopId) {
            $shopId = generateUUID();
            $pdo->prepare("
                INSERT INTO cuahang (ID, NguoiDung_id, TenCH, MoTaCH)
                VALUES (?, ?, 'FreshMart Official', 'Cửa hàng chuỗi thực phẩm tươi sạch')
            ")->execute([$shopId, $userId]);
        }

        // 3. Tạo 5 danh mục sản phẩm chuẩn
        $categories = [
            ['ID' => 'cat-01-rau-cu',   'Ten' => 'Rau củ',          'Slug' => 'rau-cu',    'Icon' => 'fas fa-carrot'],
            ['ID' => 'cat-02-trai-cay', 'Ten' => 'Trái cây',        'Slug' => 'trai-cay',  'Icon' => 'fas fa-apple-alt'],
            ['ID' => 'cat-03-thit',     'Ten' => 'Thịt & Hải sản',  'Slug' => 'thit',      'Icon' => 'fas fa-drumstick-bite'],
            ['ID' => 'cat-04-sua',      'Ten' => 'Sữa & Trứng',     'Slug' => 'sua-trung', 'Icon' => 'fas fa-cheese'],
            ['ID' => 'cat-05-khac',     'Ten' => 'Đồ uống & Hạt',   'Slug' => 'do-uong',   'Icon' => 'fas fa-seedling'],
        ];

        $catStmt = $pdo->prepare("
            INSERT IGNORE INTO danhmuc (ID, TenDanhMuc, Slug, Icon, ThuTu, TrangThai)
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        foreach ($categories as $idx => $c) {
            $catStmt->execute([$c['ID'], $c['Ten'], $c['Slug'], $c['Icon'], $idx + 1]);
        }

        // 4. Tạo 9 sản phẩm thực phẩm sạch chuẩn của FreshMart
        $products = [
            [
                'id'       => 'sp-01-ca-chua-bi',
                'cat_id'   => 'cat-01-rau-cu',
                'name'     => 'Cà chua bi Đà Lạt',
                'slug'     => 'ca-chua-bi-da-lat',
                'cost'     => 40000,
                'price'    => 32000,
                'unit'     => '500g',
                'img'      => 'img/ca-chua-bi.jpg',
                'standard' => 'VietGAP',
                'desc'     => 'Cà chua bi tươi ngon thu hoạch từ nông trại công nghệ cao tại Đà Lạt, ngọt thanh giàu vitamin C.'
            ],
            [
                'id'       => 'sp-02-chuoi-su',
                'cat_id'   => 'cat-02-trai-cay',
                'name'     => 'Chuối sứ chín tự nhiên',
                'slug'     => 'chuoi-su-chin-tu-nhien',
                'cost'     => 25000,
                'price'    => 25000,
                'unit'     => '1kg',
                'img'      => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=400&q=80',
                'standard' => 'GlobalGAP',
                'desc'     => 'Chuối sứ chín cây tự nhiên, không ủ hóa chất, dẻo ngọt giàu kali bổ dưỡng cho sức khỏe.'
            ],
            [
                'id'       => 'sp-03-thit-bo-uc',
                'cat_id'   => 'cat-03-thit',
                'name'     => 'Thịt bò Úc thăn',
                'slug'     => 'thit-bo-uc-than',
                'cost'     => 189000,
                'price'    => 189000,
                'unit'     => '500g',
                'img'      => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=400&q=80',
                'standard' => 'Nhập khẩu',
                'desc'     => 'Thịt thăn bò Úc nhập khẩu chính ngạch bảo quản lạnh -18°C, mềm ngọt đậm đà phù hợp bít tết và xào.'
            ],
            [
                'id'       => 'sp-04-sua-tuoi-th',
                'cat_id'   => 'cat-04-sua',
                'name'     => 'Sữa tươi TH True Milk',
                'slug'     => 'sua-tuoi-th-true-milk',
                'cost'     => 40000,
                'price'    => 34000,
                'unit'     => 'Hộp 1L',
                'img'      => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=400&q=80',
                'standard' => 'Hữu cơ',
                'desc'     => 'Sữa tươi tiệt trùng nguyên chất từ trang trại TH True Milk Nghệ An, giàu canxi và đạm tự nhiên.'
            ],
            [
                'id'       => 'sp-05-ca-hoi-fillet',
                'cat_id'   => 'cat-03-thit',
                'name'     => 'Cá hồi Na Uy fillet',
                'slug'     => 'ca-hoi-na-uy-fillet',
                'cost'     => 245000,
                'price'    => 245000,
                'unit'     => '300g',
                'img'      => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400&q=80',
                'standard' => 'Nhập khẩu',
                'desc'     => 'Cá hồi Na Uy fillet tươi sống giàu Omega-3 và khoáng chất, đóng gói hút chân không an toàn vệ sinh.'
            ],
            [
                'id'       => 'sp-06-rau-cai-xanh',
                'cat_id'   => 'cat-01-rau-cu',
                'name'     => 'Rau cải xanh hữu cơ',
                'slug'     => 'rau-cai-xanh-huu-co',
                'cost'     => 18000,
                'price'    => 18000,
                'unit'     => '500g',
                'img'      => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400&q=80',
                'standard' => 'VietGAP',
                'desc'     => 'Rau cải xanh hữu cơ trồng đất sạch, không thuốc trừ sâu, lá xanh mướt giòn ngọt tự nhiên.'
            ],
            [
                'id'       => 'sp-07-pho-mai-cheddar',
                'cat_id'   => 'cat-04-sua',
                'name'     => 'Phô mai Cheddar',
                'slug'     => 'pho-mai-cheddar',
                'cost'     => 95000,
                'price'    => 95000,
                'unit'     => '200g',
                'img'      => 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=400&q=80',
                'standard' => 'Nhập khẩu',
                'desc'     => 'Phô mai Cheddar truyền thống thơm béo, giàu dinh dưỡng, thích hợp cho món nướng, kẹp bánh mì và pasta.'
            ],
            [
                'id'       => 'sp-08-khoai-tay-da-lat',
                'cat_id'   => 'cat-01-rau-cu',
                'name'     => 'Khoai tây Đà Lạt',
                'slug'     => 'khoai-tay-da-lat',
                'cost'     => 30000,
                'price'    => 27000,
                'unit'     => '1kg',
                'img'      => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=400&q=80',
                'standard' => 'VietGAP',
                'desc'     => 'Khoai tây vàng Đà Lạt củ to đều, ruột vàng bùi dẻo, giàu tinh bột sạch và chất xơ tự nhiên.'
            ],
            [
                'id'       => 'sp-09-tao-my-nhap-khau',
                'cat_id'   => 'cat-02-trai-cay',
                'name'     => 'Táo Mỹ nhập khẩu',
                'slug'     => 'tao-my-nhap-khau',
                'cost'     => 65000,
                'price'    => 65000,
                'unit'     => '1kg',
                'img'      => 'img/tao-nhap-khau.jpg',
                'standard' => 'Nhập khẩu',
                'desc'     => 'Táo Envy nhập khẩu trực tiếp từ Mỹ, giòn ngọt mọng nước, vỏ đỏ bóng tự nhiên giàu chất chống oxy hóa.'
            ]
        ];

        $prodStmt = $pdo->prepare("
            INSERT IGNORE INTO sanpham (
                ID, DanhMuc_id, CuaHang_id, TenSanPham, Slug,
                GiaGoc, GiaBan, DonViTinh, SoLuongTon, HinhAnh,
                TieuChuan, MoTa, TrangThai, ThoiGianTao
            ) VALUES (
                ?, ?, ?, ?, ?,
                ?, ?, ?, 100, ?,
                ?, ?, 1, NOW()
            )
        ");

        foreach ($products as $p) {
            $prodStmt->execute([
                $p['id'],
                $p['cat_id'],
                $shopId,
                $p['name'],
                $p['slug'],
                $p['cost'],
                $p['price'],
                $p['unit'],
                $p['img'],
                $p['standard'],
                $p['desc']
            ]);
        }

    } catch (Exception $e) {
        error_log("Seed Products Error: " . $e->getMessage());
    }
}
