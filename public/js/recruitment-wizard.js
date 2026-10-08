/**
 * Portal UKM Ilmu Komputer UBBG
 * Smart Recruitment Multi-Step Wizard Engine
 * Features: Local Draft Autosave, Live Availability Check, Client-side File Pre-validation,
 * Dynamic Step Routing, Anti-Double Submit.
 */

const DRAFT_STORAGE_KEY = 'ukm_recruitment_draft_v2';
const MAX_PHOTO_BYTES = 2 * 1024 * 1024; // 2MB
const MAX_KTM_BYTES = 2 * 1024 * 1024;   // 2MB
const MAX_CV_BYTES = 3 * 1024 * 1024;    // 3MB

let currentStep = 1;
const totalSteps = 3;
let isSubmitting = false;

let nimDebounceTimer = null;
let emailDebounceTimer = null;
let autosaveDebounceTimer = null;

/* -------------------------------------------------------------
   STEP NAVIGATION & UI RENDERING
   ------------------------------------------------------------- */
function updateStepUI(step) {
    for (let i = 1; i <= totalSteps; i++) {
        const section = document.getElementById('stepSection' + i);
        const node = document.getElementById('stepNode' + i);
        const circle = document.getElementById('circleStep' + i);

        if (!section || !node || !circle) continue;

        if (i === step) {
            section.classList.add('active');
            node.classList.add('active');
            node.classList.remove('completed');
            circle.innerHTML = getStepDefaultIcon(i);
        } else if (i < step) {
            section.classList.remove('active');
            node.classList.remove('active');
            node.classList.add('completed');
            circle.innerHTML = '<i class="fas fa-check"></i>';
        } else {
            section.classList.remove('active');
            node.classList.remove('active');
            node.classList.remove('completed');
            circle.innerHTML = getStepDefaultIcon(i);
        }
    }

    const progressFill = document.getElementById('wizardProgressFill');
    if (progressFill) {
        const pct = (step / totalSteps) * 100;
        progressFill.style.width = pct + '%';
    }

    if (step === 3) {
        refreshSummary();
    }

    const container = document.querySelector('.wizard-stepper-container');
    if (container) {
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function getStepDefaultIcon(step) {
    if (step === 1) return '<i class="fas fa-user"></i>';
    if (step === 2) return '<i class="fas fa-layer-group"></i>';
    if (step === 3) return '<i class="fas fa-file-shield"></i>';
    return step;
}

function setStepAlert(stepNum, msg) {
    const alertEl = document.getElementById('stepAlert' + stepNum);
    const msgEl = document.getElementById('stepAlertMsg' + stepNum);
    if (alertEl && msgEl) {
        if (msg) {
            msgEl.innerText = msg;
            alertEl.style.display = 'flex';
            alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            alertEl.style.display = 'none';
        }
    }
}

function clearStepAlerts() {
    for (let i = 1; i <= totalSteps; i++) {
        setStepAlert(i, null);
    }
}

function hideServerErrorsAlert() {
    const el = document.getElementById('serverErrorsAlert');
    if (el) el.style.display = 'none';
}

/* -------------------------------------------------------------
   STEP-BY-STEP RIGOROUS VALIDATION
   ------------------------------------------------------------- */
function validateStep1() {
    setStepAlert(1, null);
    const fullName = document.getElementById('full_name');
    const nim = document.getElementById('nim');
    const semester = document.getElementById('semester');
    const phone = document.getElementById('phone_whatsapp');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    let isValid = true;
    let firstInvalid = null;
    let errorMessage = '';

    function mark(el, validCondition, errorMsg) {
        if (!el) return;
        if (!validCondition) {
            el.classList.add('field-invalid');
            el.classList.remove('field-valid');
            el.classList.add('shake-error');
            setTimeout(() => el.classList.remove('shake-error'), 450);
            if (isValid) {
                isValid = false;
                firstInvalid = el;
                errorMessage = errorMsg;
            }
        } else {
            el.classList.remove('field-invalid');
            el.classList.add('field-valid');
        }
    }

    mark(fullName, fullName && fullName.value.trim().length >= 3, 'Nama lengkap wajib diisi minimal 3 karakter.');
    mark(nim, nim && nim.value.trim().length >= 4, 'Nomor Induk Mahasiswa (NIM) wajib diisi dengan benar.');
    mark(semester, semester && semester.value !== '', 'Silakan pilih semester perkuliahan Anda.');
    mark(phone, phone && phone.value.replace(/[^0-9]/g, '').length >= 9, 'Nomor WhatsApp aktif wajib diisi (minimal 9 digit).');
    mark(email, email && email.value.trim().length > 0 && email.value.includes('@') && email.value.includes('.'), 'Format alamat email aktif wajib valid.');

    // Password validation for guest registration
    if (password) {
        if (password.value.length < 8) {
            mark(password, false, 'Kata sandi akun minimal 8 karakter untuk mengamankan akun mahasiswa Anda.');
        } else {
            mark(password, true, '');
            if (passwordConfirm) {
                if (passwordConfirm.value !== password.value) {
                    mark(passwordConfirm, false, 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.');
                } else {
                    mark(passwordConfirm, true, '');
                }
            }
        }
    }

    if (!isValid) {
        setStepAlert(1, errorMessage || 'Mohon lengkapi seluruh kolom bertanda bintang (*) pada Tahap 1.');
        if (firstInvalid) firstInvalid.focus();
        return false;
    }

    return true;
}

function validateStep2() {
    setStepAlert(2, null);
    const primId = document.getElementById('first_choice_division_id')?.value;
    const secId = document.getElementById('second_choice_division_id')?.value;
    const reason = document.getElementById('reason_to_join');

    let isValid = true;
    let errorMessage = '';

    if (!primId) {
        isValid = false;
        errorMessage = 'Pilihan divisi utama wajib dipilih. Silakan klik salah satu kartu divisi di bawah.';
        const cardGrid = document.querySelector('.division-cards-grid');
        if (cardGrid) {
            cardGrid.classList.add('shake-error');
            setTimeout(() => cardGrid.classList.remove('shake-error'), 450);
        }
    } else {
        const primCard = document.getElementById('divCardPrimary_' + primId);
        if (primCard && primCard.getAttribute('data-is-full') === '1') {
            isValid = false;
            errorMessage = 'Mohon maaf, kuota untuk divisi yang dipilih sudah penuh. Silakan pilih divisi lain.';
            const primInput = document.getElementById('first_choice_division_id');
            if (primInput) primInput.value = '';
            primCard.classList.remove('selected');
        }
    } else if (secId && secId === primId) {
        isValid = false;
        errorMessage = 'Divisi pilihan kedua tidak boleh sama dengan divisi pilihan utama. Silakan pilih divisi cadangan lain atau klik Reset.';
        clearSecondaryDivision();
    } else if (!reason || reason.value.trim().length < 20) {
        isValid = false;
        const currentLen = reason ? reason.value.trim().length : 0;
        errorMessage = `Alasan/motivasi bergabung minimal 20 karakter (saat ini ${currentLen} karakter). Mohon jelaskan lebih rinci.`;
        if (reason) {
            reason.classList.add('field-invalid');
            reason.classList.add('shake-error');
            setTimeout(() => reason.classList.remove('shake-error'), 450);
            reason.focus();
        }
    } else {
        if (reason) {
            reason.classList.remove('field-invalid');
            reason.classList.add('field-valid');
        }
    }

    if (!isValid) {
        setStepAlert(2, errorMessage);
        return false;
    }

    return true;
}

function validateStep3() {
    setStepAlert(3, null);

    // 1. Mandatory Profile Photo Check
    const photoInput = document.getElementById('profile_photo');
    if (!photoInput || !photoInput.files || !photoInput.files[0]) {
        setStepAlert(3, 'Pas foto resmi mahasiswa wajib diunggah.');
        highlightDropzoneError('photoDropzone');
        return false;
    }
    if (!isAllowedExtension(photoInput.files[0].name, ['jpg', 'jpeg', 'png', 'webp'])) {
        setStepAlert(3, 'Format pas foto profil harus JPG, PNG, atau WEBP.');
        highlightDropzoneError('photoDropzone');
        return false;
    }
    if (photoInput.files[0].size > MAX_PHOTO_BYTES) {
        const sizeMB = (photoInput.files[0].size / (1024 * 1024)).toFixed(2);
        setStepAlert(3, `Ukuran pas foto profil (${sizeMB} MB) melebihi batas 2MB. Silakan pilih file yang lebih kecil.`);
        highlightDropzoneError('photoDropzone');
        return false;
    }

    // 2. Mandatory KTM Check
    const ktmInput = document.getElementById('file_ktm');
    if (!ktmInput || !ktmInput.files || !ktmInput.files[0]) {
        setStepAlert(3, 'Foto atau scan KTM aktif mahasiswa wajib diunggah.');
        highlightDropzoneError('ktmDropzone');
        return false;
    }
    if (!isAllowedExtension(ktmInput.files[0].name, ['jpg', 'jpeg', 'png', 'pdf'])) {
        setStepAlert(3, 'Format file KTM harus JPG, PNG, atau PDF.');
        highlightDropzoneError('ktmDropzone');
        return false;
    }
    if (ktmInput.files[0].size > MAX_KTM_BYTES) {
        const sizeMB = (ktmInput.files[0].size / (1024 * 1024)).toFixed(2);
        setStepAlert(3, `Ukuran file KTM (${sizeMB} MB) melebihi batas 2MB. Silakan kompres foto/PDF KTM Anda.`);
        highlightDropzoneError('ktmDropzone');
        return false;
    }

    // 3. Mandatory CV Check
    const cvInput = document.getElementById('file_cv');
    if (!cvInput || !cvInput.files || !cvInput.files[0]) {
        setStepAlert(3, 'Dokumen CV / resume wajib diunggah dalam format PDF.');
        highlightDropzoneError('cvDropzone');
        return false;
    }
    if (!isAllowedExtension(cvInput.files[0].name, ['pdf'])) {
        setStepAlert(3, 'Format dokumen CV harus PDF.');
        highlightDropzoneError('cvDropzone');
        return false;
    }
    if (cvInput.files[0].size > MAX_CV_BYTES) {
        const sizeMB = (cvInput.files[0].size / (1024 * 1024)).toFixed(2);
        setStepAlert(3, `Ukuran dokumen CV (${sizeMB} MB) melebihi batas 3MB.`);
        highlightDropzoneError('cvDropzone');
        return false;
    }

    // 4. Mandatory GitHub URL Check
    const githubInput = document.getElementById('github_url');
    if (!githubInput || !githubInput.value.trim()) {
        setStepAlert(3, 'Tautan profil GitHub wajib diisi.');
        if (githubInput) {
            githubInput.classList.add('field-invalid');
            githubInput.classList.add('shake-error');
            setTimeout(() => githubInput.classList.remove('shake-error'), 450);
            githubInput.focus();
        }
        return false;
    }
    if (!githubInput.value.startsWith('http://') && !githubInput.value.startsWith('https://')) {
        setStepAlert(3, 'Tautan profil GitHub harus diawali dengan http:// atau https://');
        if (githubInput) {
            githubInput.classList.add('field-invalid');
            githubInput.focus();
        }
        return false;
    } else {
        if (githubInput) {
            githubInput.classList.remove('field-invalid');
            githubInput.classList.add('field-valid');
        }
    }

    const agree = document.getElementById('agreementCheck');
    if (!agree || !agree.checked) {
        setStepAlert(3, 'Mohon centang pernyataan persetujuan dan keabsahan dokumen sebelum mengirim pendaftaran.');
        if (agree) {
            agree.focus();
            agree.parentElement.classList.add('shake-error');
            setTimeout(() => agree.parentElement.classList.remove('shake-error'), 450);
        }
        return false;
    }

    return true;
}

function highlightDropzoneError(dropzoneId) {
    const el = document.getElementById(dropzoneId);
    if (!el) return;
    el.classList.add('shake-error');
    el.style.borderColor = '#ef4444';
    setTimeout(() => {
        el.classList.remove('shake-error');
    }, 450);
}

function validateAllSteps() {
    if (!validateStep1()) {
        currentStep = 1;
        updateStepUI(1);
        return false;
    }
    if (!validateStep2()) {
        currentStep = 2;
        updateStepUI(2);
        return false;
    }
    if (!validateStep3()) {
        currentStep = 3;
        updateStepUI(3);
        return false;
    }
    return true;
}

function nextStep(target) {
    clearStepAlerts();
    hideServerErrorsAlert();

    if (target === 2) {
        if (!validateStep1()) return;
    } else if (target === 3) {
        if (!validateStep2()) return;
    }

    currentStep = target;
    updateStepUI(currentStep);
}

function prevStep(target) {
    clearStepAlerts();
    currentStep = target;
    updateStepUI(currentStep);
}

function jumpToStep(target) {
    clearStepAlerts();
    hideServerErrorsAlert();

    if (target < currentStep) {
        currentStep = target;
        updateStepUI(currentStep);
    } else if (target === 2) {
        if (validateStep1()) {
            currentStep = 2;
            updateStepUI(2);
        }
    } else if (target === 3) {
        if (validateStep1()) {
            currentStep = 2;
            if (validateStep2()) {
                currentStep = 3;
                updateStepUI(3);
            } else {
                updateStepUI(2);
            }
        } else {
            currentStep = 1;
            updateStepUI(1);
        }
    }
}

/* -------------------------------------------------------------
   TACTILE DIVISION CARDS SELECTOR
   ------------------------------------------------------------- */
function selectPrimaryDivision(id, name, isFull) {
    hideServerErrorsAlert();
    setStepAlert(2, null);

    const card = document.getElementById('divCardPrimary_' + id);
    const fullCheck = isFull === true || (card && card.getAttribute('data-is-full') === '1');

    if (fullCheck) {
        setStepAlert(2, 'Mohon maaf, kuota untuk Divisi ' + (name || 'yang dipilih') + ' sudah penuh. Silakan pilih divisi lain yang masih tersedia.');
        return;
    }

    const primInput = document.getElementById('first_choice_division_id');
    if (primInput) primInput.value = id;

    document.querySelectorAll('.division-select-card').forEach(c => {
        c.classList.remove('selected');
    });
    if (card) {
        card.classList.add('selected');
    }

    // Auto reset secondary division if it was the same
    const secInput = document.getElementById('second_choice_division_id');
    if (secInput && secInput.value === id) {
        clearSecondaryDivision();
        setStepAlert(2, 'Divisi cadangan otomatis di-reset karena tidak boleh sama dengan divisi utama.');
    }

    // Disable matching pill in secondary division list
    document.querySelectorAll('.secondary-division-pill').forEach(pill => {
        if (pill.getAttribute('data-id') === id) {
            pill.classList.add('disabled');
        } else if (pill.getAttribute('data-is-full') !== '1') {
            pill.classList.remove('disabled');
        }
    });

    triggerAutosave();
    refreshSummary();
}

function selectSecondaryDivision(id, isFull) {
    hideServerErrorsAlert();
    setStepAlert(2, null);

    const activePill = document.getElementById('divPillSecondary_' + id);
    const fullCheck = isFull === true || (activePill && activePill.getAttribute('data-is-full') === '1');

    if (fullCheck) {
        setStepAlert(2, 'Mohon maaf, kuota untuk divisi pilihan cadangan ini sudah penuh.');
        return;
    }

    const primId = document.getElementById('first_choice_division_id')?.value;
    if (id === primId) {
        setStepAlert(2, 'Divisi cadangan tidak boleh sama dengan divisi pilihan utama.');
        return;
    }

    const secInput = document.getElementById('second_choice_division_id');
    if (secInput) secInput.value = id;

    document.querySelectorAll('.secondary-division-pill').forEach(pill => {
        pill.classList.remove('selected');
        const check = pill.querySelector('i');
        if (check) check.style.display = 'none';
    });

    if (activePill) {
        activePill.classList.add('selected');
        const check = activePill.querySelector('i');
        if (check) check.style.display = 'inline-block';
    }

    triggerAutosave();
}

function clearSecondaryDivision() {
    const secInput = document.getElementById('second_choice_division_id');
    if (secInput) secInput.value = '';

    document.querySelectorAll('.secondary-division-pill').forEach(pill => {
        pill.classList.remove('selected');
        const check = pill.querySelector('i');
        if (check) check.style.display = 'none';
    });

    triggerAutosave();
}

/* -------------------------------------------------------------
   REAL-TIME AVAILABILITY CHECKS (NIM & EMAIL)
   ------------------------------------------------------------- */
function debounceCheckNim(input) {
    hideServerErrorsAlert();
    clearTimeout(nimDebounceTimer);
    const val = input.value.trim();
    const loadIcon = document.getElementById('nimLoadingIcon');
    const validIcon = document.getElementById('nimValidIcon');
    const invalidIcon = document.getElementById('nimInvalidIcon');
    const textEl = document.getElementById('nimFeedbackText');

    if (!val || val.length < 4) {
        if (loadIcon) loadIcon.style.display = 'none';
        if (validIcon) validIcon.style.display = 'none';
        if (invalidIcon) invalidIcon.style.display = 'none';
        input.classList.remove('field-valid', 'field-invalid');
        if (textEl) {
            textEl.innerText = 'NIM akan digunakan untuk verifikasi status keanggotaan dan KTA Digital.';
            textEl.style.color = 'var(--slate-400)';
        }
        triggerAutosave();
        return;
    }

    if (loadIcon) loadIcon.style.display = 'none';
    if (validIcon) validIcon.style.display = 'none';
    if (invalidIcon) invalidIcon.style.display = 'none';

    nimDebounceTimer = setTimeout(() => {
        fetch(`/pendaftaran/check-availability?type=nim&value=${encodeURIComponent(val)}`)
            .then(r => r.json())
            .then(data => {
                if (loadIcon) loadIcon.style.display = 'none';
                if (data.available) {
                    if (validIcon) validIcon.style.display = 'block';
                    input.classList.remove('field-invalid');
                    input.classList.add('field-valid');
                    if (textEl) {
                        textEl.innerText = data.message || 'NIM siap didaftarkan.';
                        textEl.style.color = '#10b981';
                    }
                } else {
                    if (invalidIcon) invalidIcon.style.display = 'block';
                    input.classList.remove('field-valid');
                    input.classList.add('field-invalid');
                    if (textEl) {
                        textEl.innerText = data.message;
                        textEl.style.color = '#ef4444';
                    }
                }
            })
            .catch(() => {
                if (loadIcon) loadIcon.style.display = 'none';
            });
    }, 500);

    triggerAutosave();
}

function debounceCheckEmail(input) {
    hideServerErrorsAlert();
    clearTimeout(emailDebounceTimer);
    const val = input.value.trim();
    const loadIcon = document.getElementById('emailLoadingIcon');
    const validIcon = document.getElementById('emailValidIcon');
    const invalidIcon = document.getElementById('emailInvalidIcon');
    const textEl = document.getElementById('emailFeedbackText');

    if (!val || !val.includes('@') || !val.includes('.')) {
        if (loadIcon) loadIcon.style.display = 'none';
        if (validIcon) validIcon.style.display = 'none';
        if (invalidIcon) invalidIcon.style.display = 'none';
        input.classList.remove('field-valid', 'field-invalid');
        triggerAutosave();
        return;
    }

    if (loadIcon) loadIcon.style.display = 'none';
    if (validIcon) validIcon.style.display = 'none';
    if (invalidIcon) invalidIcon.style.display = 'none';

    emailDebounceTimer = setTimeout(() => {
        fetch(`/pendaftaran/check-availability?type=email&value=${encodeURIComponent(val)}`)
            .then(r => r.json())
            .then(data => {
                if (loadIcon) loadIcon.style.display = 'none';
                if (data.available) {
                    if (validIcon) validIcon.style.display = 'block';
                    input.classList.remove('field-invalid');
                    input.classList.add('field-valid');
                    if (textEl) {
                        textEl.innerText = data.message || 'Email valid & siap digunakan.';
                        textEl.style.color = '#10b981';
                    }
                } else {
                    if (invalidIcon) invalidIcon.style.display = 'block';
                    input.classList.remove('field-valid');
                    input.classList.add('field-invalid');
                    if (textEl) {
                        textEl.innerText = data.message;
                        textEl.style.color = '#ef4444';
                    }
                }
            })
            .catch(() => {
                if (loadIcon) loadIcon.style.display = 'none';
            });
    }, 500);

    triggerAutosave();
}

/* -------------------------------------------------------------
   PASSWORD TOGGLE & STRENGTH METER
   ------------------------------------------------------------- */
function togglePasswordVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

function checkPasswordStrength(input) {
    hideServerErrorsAlert();
    const val = input.value;
    const fill = document.getElementById('passwordMeterFill');
    const label = document.getElementById('passwordStrengthLabel');

    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    if (fill && label) {
        fill.className = 'password-meter-fill';
        if (val.length === 0) {
            fill.style.width = '0%';
            label.innerText = 'Kekuatan: -';
            label.style.color = 'var(--slate-400)';
        } else if (score <= 1 || val.length < 8) {
            fill.classList.add('strength-weak');
            label.innerText = 'Kekuatan: Lemah';
            label.style.color = '#ef4444';
        } else if (score === 2 || score === 3) {
            fill.classList.add('strength-medium');
            label.innerText = 'Kekuatan: Cukup Baik';
            label.style.color = '#f59e0b';
        } else {
            fill.classList.add('strength-strong');
            label.innerText = 'Kekuatan: Sangat Kuat';
            label.style.color = '#10b981';
        }
    }

    checkPasswordMatch();
}

function checkPasswordMatch() {
    const pass = document.getElementById('password');
    const passConfirm = document.getElementById('password_confirmation');
    const matchText = document.getElementById('passwordMatchText');

    if (!pass || !passConfirm || !matchText) return;

    if (passConfirm.value.length === 0) {
        matchText.innerText = 'Ketik ulang kata sandi yang sama.';
        matchText.style.color = 'var(--slate-400)';
        passConfirm.classList.remove('field-valid', 'field-invalid');
        return;
    }

    if (pass.value === passConfirm.value) {
        matchText.innerText = '✓ Kata sandi cocok!';
        matchText.style.color = '#10b981';
        passConfirm.classList.remove('field-invalid');
        passConfirm.classList.add('field-valid');
    } else {
        matchText.innerText = '✗ Kata sandi belum cocok.';
        matchText.style.color = '#ef4444';
        passConfirm.classList.remove('field-valid');
        passConfirm.classList.add('field-invalid');
    }
}

function formatWhatsapp(input) {
    hideServerErrorsAlert();
    let val = input.value.replace(/[^0-9]/g, '');
    if (val.startsWith('62')) {
        val = '0' + val.substring(2);
    }
    input.value = val;
    triggerAutosave();
}

function countReasonChars(textarea) {
    hideServerErrorsAlert();
    setStepAlert(2, null);
    const len = textarea.value.length;
    const countSpan = document.getElementById('reasonCharCount');
    const display = document.getElementById('charCountDisplay');
    const hint = document.getElementById('reasonQualityHint');

    if (countSpan) countSpan.innerText = len;

    if (display && hint) {
        if (len >= 60) {
            display.style.color = '#10b981';
            hint.innerText = 'Luar biasa! Motivasi Anda sangat komprehensif.';
            hint.style.color = '#10b981';
            textarea.classList.remove('field-invalid');
            textarea.classList.add('field-valid');
        } else if (len >= 20) {
            display.style.color = '#2563eb';
            hint.innerText = 'Bagus! Sudah memenuhi syarat minimal 20 karakter.';
            hint.style.color = '#2563eb';
            textarea.classList.remove('field-invalid');
            textarea.classList.add('field-valid');
        } else {
            display.style.color = 'var(--slate-400)';
            hint.innerText = `Kurang ${20 - len} karakter lagi.`;
            hint.style.color = '#ef4444';
        }
    }

    triggerAutosave();
}

function autoFormatUrl(input) {
    let val = input.value.trim();
    if (val && !val.startsWith('http://') && !val.startsWith('https://')) {
        input.value = 'https://' + val;
    }
    triggerAutosave();
}

function isAllowedExtension(filename, allowedExts) {
    if (!filename || !filename.includes('.')) return false;
    const ext = filename.split('.').pop().toLowerCase();
    return allowedExts.includes(ext);
}

function handlePhotoSelect(input) {
    setFileDropzoneFeedback('photoDropzone', null);
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    // INSTANT CLIENT-SIDE EXTENSION CHECK
    if (!isAllowedExtension(file.name, ['jpg', 'jpeg', 'png', 'webp'])) {
        input.value = '';
        removePhotoUpload();
        setFileDropzoneFeedback('photoDropzone', '❌ Format pas foto harus JPG, PNG, atau WEBP.');
        return;
    }

    // INSTANT CLIENT-SIDE FILE SIZE CHECK
    if (file.size > MAX_PHOTO_BYTES) {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        input.value = '';
        removePhotoUpload();
        setFileDropzoneFeedback('photoDropzone', `❌ Ukuran foto (${sizeMB} MB) melebihi batas 2MB. Silakan pilih foto dengan resolusi lebih kecil.`);
        return;
    }

    const preview = document.getElementById('photoDropzonePreview');
    const thumb = document.getElementById('photoThumbImg');
    const nameEl = document.getElementById('photoFileName');
    const sizeEl = document.getElementById('photoFileSize');

    if (nameEl) nameEl.innerText = file.name;
    if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB (Siap diunggah)';

    const reader = new FileReader();
    reader.onload = function(e) {
        if (thumb) thumb.src = e.target.result;
        if (preview) preview.style.display = 'flex';
    };
    reader.readAsDataURL(file);
}

function removePhotoUpload(e) {
    if (e) e.stopPropagation();
    const input = document.getElementById('profile_photo');
    if (input) input.value = '';
    const preview = document.getElementById('photoDropzonePreview');
    if (preview) preview.style.display = 'none';
    setFileDropzoneFeedback('photoDropzone', null);
}

function handleFileKtmSelect(input) {
    setFileDropzoneFeedback('ktmDropzone', null);
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    // INSTANT CLIENT-SIDE EXTENSION CHECK
    if (!isAllowedExtension(file.name, ['jpg', 'jpeg', 'png', 'pdf'])) {
        input.value = '';
        removeKtmUpload();
        setFileDropzoneFeedback('ktmDropzone', '❌ Format KTM harus berupa JPG, PNG, atau PDF.');
        return;
    }

    // INSTANT CLIENT-SIDE FILE SIZE CHECK
    if (file.size > MAX_KTM_BYTES) {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        input.value = '';
        removeKtmUpload();
        setFileDropzoneFeedback('ktmDropzone', `❌ Ukuran KTM (${sizeMB} MB) melebihi batas 2MB. Silakan kompres atau pilih berkas lain.`);
        return;
    }

    const preview = document.getElementById('ktmDropzonePreview');
    const nameEl = document.getElementById('ktmFileName');
    const sizeEl = document.getElementById('ktmFileSize');

    if (nameEl) nameEl.innerText = file.name;
    if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB (Terverifikasi)';
    if (preview) preview.style.display = 'flex';
}

function removeKtmUpload(e) {
    if (e) e.stopPropagation();
    const input = document.getElementById('file_ktm');
    if (input) input.value = '';
    const preview = document.getElementById('ktmDropzonePreview');
    if (preview) preview.style.display = 'none';
    setFileDropzoneFeedback('ktmDropzone', null);
}

function handleFileCvSelect(input) {
    setFileDropzoneFeedback('cvDropzone', null);
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    // INSTANT CLIENT-SIDE EXTENSION CHECK
    if (!isAllowedExtension(file.name, ['pdf'])) {
        input.value = '';
        removeCvUpload();
        setFileDropzoneFeedback('cvDropzone', '❌ Format CV / Resume harus dokumen PDF.');
        return;
    }

    // INSTANT CLIENT-SIDE FILE SIZE CHECK
    if (file.size > MAX_CV_BYTES) {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        input.value = '';
        removeCvUpload();
        setFileDropzoneFeedback('cvDropzone', `❌ Ukuran CV (${sizeMB} MB) melebihi batas 3MB.`);
        return;
    }

    const preview = document.getElementById('cvDropzonePreview');
    const nameEl = document.getElementById('cvFileName');
    const sizeEl = document.getElementById('cvFileSize');

    if (nameEl) nameEl.innerText = file.name;
    if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB (Siap diunggah)';
    if (preview) preview.style.display = 'flex';
}

function removeCvUpload(e) {
    if (e) e.stopPropagation();
    const input = document.getElementById('file_cv');
    if (input) input.value = '';
    const preview = document.getElementById('cvDropzonePreview');
    if (preview) preview.style.display = 'none';
    setFileDropzoneFeedback('cvDropzone', null);
}

function setFileDropzoneFeedback(dropzoneId, errorMsg) {
    const errorEl = document.getElementById(dropzoneId + 'Error');
    const dropzone = document.getElementById(dropzoneId);
    if (errorEl) {
        if (errorMsg) {
            errorEl.innerText = errorMsg;
            errorEl.style.display = 'block';
            if (dropzone) {
                dropzone.style.borderColor = '#ef4444';
                dropzone.classList.add('shake-error');
                setTimeout(() => dropzone.classList.remove('shake-error'), 450);
            }
        } else {
            errorEl.style.display = 'none';
            if (dropzone) dropzone.style.borderColor = '#cbd5e1';
        }
    }
}

// Drag & drop event registration
function initDropzones() {
    ['photoDropzone', 'ktmDropzone', 'cvDropzone'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        ['dragenter', 'dragover'].forEach(eventName => {
            el.addEventListener(eventName, (e) => {
                e.preventDefault();
                el.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            el.addEventListener(eventName, (e) => {
                e.preventDefault();
                el.classList.remove('dragover');
                if (eventName === 'drop' && e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                    const fileInput = el.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.files = e.dataTransfer.files;
                        fileInput.dispatchEvent(new Event('change'));
                    }
                }
            }, false);
        });
    });
}

/* -------------------------------------------------------------
   AUTOSAVE DRAFT & LOCALSTORAGE RESILIENCY
   ------------------------------------------------------------- */
function triggerAutosave() {
    clearTimeout(autosaveDebounceTimer);
    autosaveDebounceTimer = setTimeout(() => {
        saveDraftToStorage();
    }, 400);
}

function saveDraftToStorage() {
    const draft = {
        full_name: document.getElementById('full_name')?.value || '',
        nim: document.getElementById('nim')?.value || '',
        semester: document.getElementById('semester')?.value || '',
        phone_whatsapp: document.getElementById('phone_whatsapp')?.value || '',
        email: document.getElementById('email')?.value || '',
        class_group: document.getElementById('class_group')?.value || '',
        first_choice_division_id: document.getElementById('first_choice_division_id')?.value || '',
        second_choice_division_id: document.getElementById('second_choice_division_id')?.value || '',
        reason_to_join: document.getElementById('reason_to_join')?.value || '',
        github_url: document.getElementById('github_url')?.value || '',
        portfolio_url: document.getElementById('portfolio_url')?.value || '',
        timestamp: new Date().getTime()
    };

    if (draft.full_name || draft.nim || draft.reason_to_join) {
        localStorage.setItem(DRAFT_STORAGE_KEY, JSON.stringify(draft));
        showAutosaveToast();
    }
}

function showAutosaveToast() {
    const toast = document.getElementById('autosaveToast');
    if (!toast) return;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2000);
}

function checkExistingDraft() {
    const raw = localStorage.getItem(DRAFT_STORAGE_KEY);
    if (!raw) return;

    try {
        const draft = JSON.parse(raw);
        const currentName = document.getElementById('full_name')?.value || '';
        const currentNim = document.getElementById('nim')?.value || '';

        if (!currentName && !currentNim && (draft.full_name || draft.nim)) {
            const banner = document.getElementById('draftRecoveryBanner');
            if (banner) banner.style.display = 'flex';
            applyDraftValues(draft);
        }
    } catch (e) {
        console.error('Error loading draft', e);
    }
}

function applyDraftValues(draft) {
    if (!draft) return;
    if (draft.full_name) document.getElementById('full_name').value = draft.full_name;
    if (draft.nim) document.getElementById('nim').value = draft.nim;
    if (draft.semester) document.getElementById('semester').value = draft.semester;
    if (draft.phone_whatsapp) document.getElementById('phone_whatsapp').value = draft.phone_whatsapp;
    if (draft.email && !document.getElementById('email').readOnly) document.getElementById('email').value = draft.email;
    if (draft.class_group) document.getElementById('class_group').value = draft.class_group;
    if (draft.reason_to_join) {
        const reason = document.getElementById('reason_to_join');
        if (reason) {
            reason.value = draft.reason_to_join;
            countReasonChars(reason);
        }
    }
    if (draft.github_url) document.getElementById('github_url').value = draft.github_url;
    if (draft.portfolio_url) document.getElementById('portfolio_url').value = draft.portfolio_url;

    if (draft.first_choice_division_id) {
        const card = document.getElementById('divCardPrimary_' + draft.first_choice_division_id);
        if (card) {
            selectPrimaryDivision(draft.first_choice_division_id, card.getAttribute('data-name') || '');
        }
    }

    if (draft.second_choice_division_id) {
        selectSecondaryDivision(draft.second_choice_division_id);
    }
}

function dismissDraftBanner() {
    const banner = document.getElementById('draftRecoveryBanner');
    if (banner) banner.style.display = 'none';
}

function clearDraftAndReset() {
    localStorage.removeItem(DRAFT_STORAGE_KEY);
    const banner = document.getElementById('draftRecoveryBanner');
    if (banner) banner.style.display = 'none';
    document.getElementById('recruitmentMultiStepForm')?.reset();
    clearSecondaryDivision();
    document.querySelectorAll('.division-select-card').forEach(c => c.classList.remove('selected'));
    const primInput = document.getElementById('first_choice_division_id');
    if (primInput) primInput.value = '';
    currentStep = 1;
    updateStepUI(1);
}

/* -------------------------------------------------------------
   LIVE SUMMARY REFRESH
   ------------------------------------------------------------- */
function refreshSummary() {
    const nameVal = document.getElementById('full_name')?.value || '-';
    const nimVal = document.getElementById('nim')?.value || '-';
    const semVal = document.getElementById('semester')?.value;
    const emailVal = document.getElementById('email')?.value || '-';

    const primId = document.getElementById('first_choice_division_id')?.value;
    let divText = '-';
    if (primId) {
        const card = document.getElementById('divCardPrimary_' + primId);
        if (card) {
            divText = card.getAttribute('data-name') || '-';
        }
    }

    const nameEl = document.getElementById('summaryName');
    const nimSemEl = document.getElementById('summaryNimSemester');
    const divEl = document.getElementById('summaryDivision');
    const emailEl = document.getElementById('summaryEmail');

    if (nameEl) nameEl.innerText = nameVal;
    if (nimSemEl) nimSemEl.innerText = nimVal + (semVal ? ' (Semester ' + semVal + ')' : '');
    if (divEl) divEl.innerText = divText;
    if (emailEl) emailEl.innerText = emailVal;
}

/* -------------------------------------------------------------
   BOOTSTRAP WIZARD INITIALIZER
   ------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
    initDropzones();

    ['full_name', 'semester', 'class_group', 'github_url', 'portfolio_url'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', () => {
            hideServerErrorsAlert();
            triggerAutosave();
        });
    });

    checkExistingDraft();

    const reasonEl = document.getElementById('reason_to_join');
    if (reasonEl) countReasonChars(reasonEl);

    const preId = document.getElementById('first_choice_division_id')?.value;
    if (preId) {
        const card = document.getElementById('divCardPrimary_' + preId);
        if (card) {
            selectPrimaryDivision(preId, card.getAttribute('data-name') || '');
        }
    }

    // Form submit listener
    const form = document.getElementById('recruitmentMultiStepForm');
    const submitBtn = document.getElementById('submitFormBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            if (!validateAllSteps()) {
                e.preventDefault();
                return;
            }

            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.85';
            submitBtn.style.cursor = 'not-allowed';
            if (submitIcon) submitIcon.className = 'fas fa-circle-notch fa-spin';
            if (submitText) submitText.innerText = 'Mengunggah Berkas & Mendaftarkan Akun...';

            localStorage.removeItem(DRAFT_STORAGE_KEY);
        });
    }
});
