<!-- ═══════ ĐĂNG NHẬP ═══════ -->
        <?php if ($page === 'auth'): ?>
            <div class="card" id="form-login" style="border-top: 2px solid var(--accent);">
                <div style="text-align:center; margin-bottom:24px;">
                    <div
                        style="display:inline-flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:16px; background:rgba(167,139,250,0.1); color:var(--accent-bright); margin-bottom:16px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                    </div>
                    <h2 class="page-title light">Đăng nhập</h2>
                    <p class="desc">Sử dụng tài khoản đã đăng ký trên hệ thống.</p>
                </div>
                <form action="/dang-nhap" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="field"><label>Tài khoản đăng nhập</label><input type="text" name="username" class="input"
                            placeholder="Viết liền, không dấu (VD: NguyenVanA)" required></div>
                    <div class="field"><label>Mật khẩu</label><input type="password" name="password" class="input"
                            placeholder="Nhập mật khẩu" required></div>
                    <button type="submit" class="btn btn-primary mt-sm">Đăng nhập</button>
                </form>
                <div class="divider"></div>
                <p class="desc text-center">Chưa có tài khoản? <a href="javascript:void(0)" class="link"
                        onclick="document.getElementById('form-login').style.display='none';document.getElementById('form-reg').style.display='block';">Đăng
                        ký ngay</a></p>
            </div>
            <div class="card" id="form-reg" style="display:none; border-top: 2px solid var(--green);">
                <div style="text-align:center; margin-bottom:24px;">
                    <div
                        style="display:inline-flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:16px; background:rgba(52,211,153,0.1); color:var(--green); margin-bottom:16px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </div>
                    <h2 class="page-title light">Tạo tài khoản</h2>
                    <p class="desc">Tài khoản mới sẽ ở cấp Thành viên. Nâng cấp VIP tại Bảng giá.</p>
                </div>
                <form action="/dang-nhap" method="POST">
                    <input type="hidden" name="action" value="register">
                    <div class="field"><label>Số điện thoại xác thực</label><input type="text" name="phone" class="input"
                            placeholder="Zalo nhận OTP kích hoạt" title="Vui lòng nhập số điện thoại hợp lệ"
                            pattern="[0-9]{9,12}" required></div>
                    <div class="field">
                        <label>Tài khoản đăng nhập</label>
                        <input type="text" name="username" id="reg-username"
                            class="input" placeholder="Viết liền, không dấu (VD: NguyenVanA)" 
                            pattern="[a-zA-Z0-9._]{3,30}" 
                            title="Chỉ cho phép chữ cái (a-z, A-Z), số (0-9), dấu chấm (.) và gạch dưới (_). Từ 3-30 ký tự, không dấu, không khoảng trắng."
                            required>
                        <small style="color:var(--text-2); font-size:11px; margin-top:4px; display:block;">Chỉ dùng chữ cái, số, dấu chấm hoặc gạch dưới. Không khoảng trắng, không dấu tiếng Việt.</small>
                    </div>
                    <div class="field"><label>Mật khẩu</label><input type="password" name="password" class="input"
                            placeholder="Ít nhất 6 ký tự" minlength="6" required></div>
                    <div class="field"><label>Mã giới thiệu (Nếu có)</label><input type="text" name="referred_by"
                            class="input" placeholder="Nhập mã giới thiệu (Tuỳ chọn)" style="text-transform: uppercase;">
                    </div>
                    <div
                        style="font-size:12px; color:var(--red); padding:10px; border:1px dashed rgba(248,113,113,0.4); border-radius:8px; line-height:1.5; margin-bottom:16px; background:rgba(239,68,68,0.1);">
                        ⚠️ <b>Yêu cầu bắt buộc:</b> Vui lòng điền ĐÚNG SỐ ĐIỆN THOẠI chính chủ (ưu tiên Zalo). Hệ thống sẽ
                        sử dụng số điện thoại này để gửi mã rà soát chống BOT tự động.
                    </div>
                    <button type="submit" class="btn btn-primary mt-sm">Tạo tài khoản</button>
                </form>
                <div class="divider"></div>
                <p class="desc text-center">Đã có tài khoản? <a href="javascript:void(0)" class="link"
                        onclick="document.getElementById('form-reg').style.display='none';document.getElementById('form-login').style.display='block';">Đăng
                        nhập</a></p>
            </div>
        <script>
        (function(){
            // Bảng chuyển đổi tiếng Việt có dấu → không dấu (giữ nguyên hoa/thường)
            const vn_lower = {'à':'a','á':'a','ả':'a','ã':'a','ạ':'a','ă':'a','ằ':'a','ắ':'a','ẳ':'a','ẵ':'a','ặ':'a','â':'a','ầ':'a','ấ':'a','ẩ':'a','ẫ':'a','ậ':'a','đ':'d','è':'e','é':'e','ẻ':'e','ẽ':'e','ẹ':'e','ê':'e','ề':'e','ế':'e','ể':'e','ễ':'e','ệ':'e','ì':'i','í':'i','ỉ':'i','ĩ':'i','ị':'i','ò':'o','ó':'o','ỏ':'o','õ':'o','ọ':'o','ô':'o','ồ':'o','ố':'o','ổ':'o','ỗ':'o','ộ':'o','ơ':'o','ờ':'o','ớ':'o','ở':'o','ỡ':'o','ợ':'o','ù':'u','ú':'u','ủ':'u','ũ':'u','ụ':'u','ư':'u','ừ':'u','ứ':'u','ử':'u','ữ':'u','ự':'u','ỳ':'y','ý':'y','ỷ':'y','ỹ':'y','ỵ':'y'};
            const vn_upper = {'À':'A','Á':'A','Ả':'A','Ã':'A','Ạ':'A','Ă':'A','Ằ':'A','Ắ':'A','Ẳ':'A','Ẵ':'A','Ặ':'A','Â':'A','Ầ':'A','Ấ':'A','Ẩ':'A','Ẫ':'A','Ậ':'A','Đ':'D','È':'E','É':'E','Ẻ':'E','Ẽ':'E','Ẹ':'E','Ê':'E','Ề':'E','Ế':'E','Ể':'E','Ễ':'E','Ệ':'E','Ì':'I','Í':'I','Ỉ':'I','Ĩ':'I','Ị':'I','Ò':'O','Ó':'O','Ỏ':'O','Õ':'O','Ọ':'O','Ô':'O','Ồ':'O','Ố':'O','Ổ':'O','Ỗ':'O','Ộ':'O','Ơ':'O','Ờ':'O','Ớ':'O','Ở':'O','Ỡ':'O','Ợ':'O','Ù':'U','Ú':'U','Ủ':'U','Ũ':'U','Ụ':'U','Ư':'U','Ừ':'U','Ứ':'U','Ử':'U','Ữ':'U','Ự':'U','Ỳ':'Y','Ý':'Y','Ỷ':'Y','Ỹ':'Y','Ỵ':'Y'};
            const vn_map = Object.assign({}, vn_lower, vn_upper);
            function removeVietnamese(str) {
                return str.split('').map(c => vn_map[c] || c).join('');
            }
            function sanitizeUsername(val) {
                val = removeVietnamese(val);
                val = val.replace(/\s+/g, '');
                val = val.replace(/[^a-zA-Z0-9._]/g, '');
                return val;
            }
            // Áp dụng cho input đăng ký
            const regInput = document.getElementById('reg-username');
            if (regInput) {
                regInput.addEventListener('input', function() {
                    const pos = this.selectionStart;
                    const before = this.value.length;
                    this.value = sanitizeUsername(this.value);
                    const after = this.value.length;
                    this.setSelectionRange(pos - (before - after), pos - (before - after));
                });
            }
            // Áp dụng cho input đăng nhập (xóa khoảng trắng)
            const loginInputs = document.querySelectorAll('#form-login input[name="username"]');
            loginInputs.forEach(function(inp) {
                inp.addEventListener('input', function() {
                    this.value = this.value.replace(/\s+/g, '');
                });
            });
        })();
        </script>
        <?php endif; ?>