<?php
/**
 * Test logic phân cấp thẩm quyền permissions
 */

function checkPermissionRule(string $currentRole, string $targetRole, string $newRole): array {
    $currentRole = strtoupper($currentRole);
    $targetRole  = strtoupper($targetRole);
    $newRole     = strtoupper($newRole);

    if (!in_array($currentRole, ['OWNER', 'ADMIN', 'QUAN_TRI', 'MANAGER', 'STAFF'])) {
        return ['allowed' => false, 'reason' => 'Không có quyền truy cập'];
    }

    $allowedRoles = ['OWNER', 'MANAGER', 'STAFF', 'USER'];
    if (!in_array($newRole, $allowedRoles)) {
        return ['allowed' => false, 'reason' => 'Vai trò không hợp lệ'];
    }

    // 1. Quản trị viên
    if (in_array($currentRole, ['OWNER', 'ADMIN', 'QUAN_TRI'])) {
        return ['allowed' => true, 'reason' => 'Quản trị viên điều chỉnh tất cả'];
    }

    // 2. Quản lý Cửa hàng
    if ($currentRole === 'MANAGER') {
        if (!in_array($targetRole, ['STAFF', 'USER'])) {
            return ['allowed' => false, 'reason' => 'Quản lý Cửa hàng chỉ được phép điều chỉnh Nhân viên và Khách hàng'];
        }
        if (!in_array($newRole, ['STAFF', 'USER'])) {
            return ['allowed' => false, 'reason' => 'Quản lý Cửa hàng chỉ có quyền cấp vai trò STAFF hoặc USER'];
        }
        return ['allowed' => true, 'reason' => 'Quản lý điều chỉnh Nhân viên và Khách hàng'];
    }

    // 3. Nhân viên
    if ($currentRole === 'STAFF') {
        if ($targetRole !== 'USER') {
            return ['allowed' => false, 'reason' => 'Nhân viên chỉ có quyền điều chỉnh Khách hàng'];
        }
        if ($newRole !== 'USER') {
            return ['allowed' => false, 'reason' => 'Nhân viên không thể nâng cấp lên vai trò khác'];
        }
        return ['allowed' => true, 'reason' => 'Nhân viên điều chỉnh Khách hàng'];
    }

    return ['allowed' => false, 'reason' => 'Không được phép'];
}

// Bắt đầu test nếu chạy trực tiếp
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $tests = [
        // OWNER tests
        ['OWNER', 'OWNER', 'MANAGER', true],
        ['OWNER', 'MANAGER', 'STAFF', true],
        ['OWNER', 'STAFF', 'USER', true],
        ['OWNER', 'USER', 'OWNER', true],

        // MANAGER tests
        ['MANAGER', 'STAFF', 'USER', true],
        ['MANAGER', 'USER', 'STAFF', true],
        ['MANAGER', 'MANAGER', 'STAFF', false], // Không sửa được Quản lý khác
        ['MANAGER', 'OWNER', 'USER', false],    // Không sửa được Owner
        ['MANAGER', 'USER', 'MANAGER', false],  // Không nâng lên Quản lý
        ['MANAGER', 'USER', 'OWNER', false],    // Không nâng lên Owner

        // STAFF tests
        ['STAFF', 'USER', 'USER', true],        // Sửa được khách hàng
        ['STAFF', 'USER', 'STAFF', false],      // Không nâng được lên Staff
        ['STAFF', 'USER', 'MANAGER', false],    // Không nâng được lên Manager
        ['STAFF', 'USER', 'OWNER', false],      // Không nâng được lên Owner
        ['STAFF', 'STAFF', 'USER', false],      // Không sửa được Staff
        ['STAFF', 'MANAGER', 'USER', false],    // Không sửa được Manager
        ['STAFF', 'OWNER', 'USER', false],      // Không sửa được Owner

        // USER tests
        ['USER', 'USER', 'USER', false],
    ];

    $passed = 0;
    foreach ($tests as $i => $t) {
        [$actor, $target, $new, $expected] = $t;
        $res = checkPermissionRule($actor, $target, $new);
        if ($res['allowed'] === $expected) {
            echo "[PASS] Test $i: $actor chỉnh $target -> $new: " . ($expected ? "ALLOWED" : "DENIED") . " ({$res['reason']})\n";
            $passed++;
        } else {
            echo "[FAIL] Test $i: $actor chỉnh $target -> $new: Expected " . ($expected ? "ALLOWED" : "DENIED") . " but got " . ($res['allowed'] ? "ALLOWED" : "DENIED") . "\n";
        }
    }

    echo "\nTổng số: " . count($tests) . " | Thành công: $passed | Thất bại: " . (count($tests) - $passed) . "\n";
    if ($passed === count($tests)) {
        echo ">>> TẤT CẢ QUY TẮC PHÂN CẤP THẨM QUYỀN CHUẨN XÁC 100%! <<<\n";
    }
}
