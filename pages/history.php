<!-- ═══════ QUẢN LÝ ═══════ -->
        <?php if ($page === 'history'): ?>
            <?php if (!$current_user) {
                echo "<script>window.location='/dang-nhap';</script>";
                exit;
            } ?>
            <div class="page-shell" style="display:flex; flex-direction:column; gap:20px;">
                <div class="card card-wide">
                    <h2 class="page-title light mb-sm">Lịch sử kích hoạt</h2>
                    <p class="desc text-center mb-md">Danh sách các lần kích hoạt Locket Gold trên hệ thống.</p>
                    <div style="overflow-x:auto;">
                        <?php
                        $query = "SELECT * FROM activations WHERE injected_by = ? ORDER BY created_at DESC LIMIT 50";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$current_user]);
                        $rows = $stmt->fetchAll();
                        ?>
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Locket ID</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày</th>
                                    <?php if (in_array($current_role, ['admin', 'agency'])): ?>
                                        <th style="width:80px; text-align:center;">Xóa</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($rows) == 0): ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center; padding:40px; color:var(--text-2);">Chưa có dữ
                                            liệu lịch sử.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($rows as $r): ?>
                                        <tr>
                                            <td data-label="Locket ID"
                                                style="font-family:monospace; font-size:13px; color:var(--accent-bright); word-break:break-all; max-width:200px;">
                                                <?= htmlspecialchars($r['uid']) ?>
                                            </td>
                                            <td data-label="Trạng thái">
                                                <?php $st = strtolower($r['status']);
                                                $ok = (strpos($st, 'live') !== false || strpos($st, 'demo') !== false || strpos($st, 'success') !== false);
                                                echo '<span class="tag ' . ($ok ? 'tag-green' : 'tag-red') . '">' . htmlspecialchars($r['status']) . '</span>'; ?>
                                            </td>
                                            <td data-label="Ngày" style="font-size:13px; color:var(--text-2);">
                                                <?= date('d/m H:i', strtotime($r['created_at'])) ?>
                                            </td>
                                            <?php if (in_array($current_role, ['admin', 'agency'])): ?>
                                                <td data-label="Xóa" style="text-align:center;">
                                                    <form method="POST" action="/lich-su"
                                                        onsubmit="return confirm('Xóa ID này khỏi lịch sử?');" style="display:inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="action" value="delete_activation">
                                                        <input type="hidden" name="activation_id" value="<?= (int) $r['id'] ?>">
                                                        <button type="submit"
                                                            style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#ef4444; border-radius:8px; padding:5px 10px; cursor:pointer; font-size:12px; font-weight:600; transition:all 0.2s;"
                                                            onmouseover="this.style.background='rgba(239,68,68,0.25)';"
                                                            onmouseout="this.style.background='rgba(239,68,68,0.1)';"
                                                            title="Xóa ID này">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2.5">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>