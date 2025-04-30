<svg style="display: none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255); display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
    <circle cx="50" cy="50" fill="none" stroke="#1d0e0b" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
    </circle>
</svg>
<!-- <div class="login-wrapper" style="list-style: none;">
    <h2 class="login-title">Masuk</h2>
    <form id="login" novalidate action="{{ route('authenticate') }}" method="POST">
        @csrf
        @method('POST')
        <div class="alert alert-danger" style="display: none"></div>
        <div class="form-group">
            <label for="userid" class="sr-only">User ID</label>
            <input required type="text" name="userid" id="userid" class="form-control" placeholder="User ID">
            <div class="invalid-feedback">User ID harus diisi.</div>
        </div>
        <div class="form-group mb-3">
            <label for="password" class="sr-only">Password</label>
            <input required type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi">
            <div class="invalid-feedback">Password harus diisi.</div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-5">
            <button name="login" id="login" class="btn login-btn" type="submit">
                Login
            </button>
        </div>
    </form>
    <p class="login-wrapper-footer-text">Belum punya akun ? <a href="javascript:void(0)" class="text-reset text-success font-weight-bold">Daftar di sini.</a></p>
</div> -->

<div class="login-wrapper" style="list-style: none;">
    <h2 class="login-title">Masuk</h2>
    <form id="login" novalidate action="{{ route('authenticate') }}" method="POST">
        @csrf
        @method('POST')
        <div class="alert alert-danger" style="display: none"></div>
        <div class="form-group">
            <label for="userid" class="sr-only">User ID</label>
            <input required type="text" name="userid" id="userid" class="form-control" placeholder="User ID">
            <div class="invalid-feedback">User ID harus diisi.</div>
        </div>
        <div class="form-group mb-3">
            <label for="password" class="sr-only">Password</label>
            <div class="input-group password-input-group">
                <input required type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi">
                <div class="input-group-append">
                    <button class="btn btn-secondary toggle-password p-0" type="button" style="width: 2.5rem;">
                        <i class="fas fa-eye" style="font-size: 1rem;"></i>
                    </button>
                </div>
            </div>
            <div class="invalid-feedback">Password harus diisi.</div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-5">
            <button name="login" id="btnLogin" class="btn login-btn" type="submit">
                Login
            </button>
        </div>
    </form>
    <p class="login-wrapper-footer-text">Belum punya akun ? <a href="javascript:void(0)" class="text-reset text-success font-weight-bold">Daftar di sini.</a></p>
</div>

<style>
    /* Custom adjustments for perfect icon sizing */
    .password-input-group .input-group-append {
        width: auto;
    }

    .input-group-append {
        margin-bottom: 7px;
    }

    .toggle-password {
        border-left: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .toggle-password:hover {
        background-color: black;
    }

    /* Match the exact height of the input */
    .toggle-password i {
        font-size: 1rem;
        width: 1em;
        height: 1em;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<script>
    (() => {
        'use strict';

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('#login');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    let passwordTimeout;
    document.querySelector('.toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');

        clearTimeout(passwordTimeout);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            passwordTimeout = setTimeout(() => {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }, 5000);
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    $('form').on('submit', function(event) {
        event.preventDefault()
        document.querySelector('.login-wrapper').style.display = "none"
        document.querySelector('svg').style.display = "inherit"
        console.log($(this).serialize())
        $.ajax({
            url: "{{ route('authenticate') }}",
            type: "POST",
            data: $(this).serialize(),
            success: (res) => {
                window.location.reload()
            },
            error: (err) => {
                err = err.responseJSON.errors
                document.querySelector('form').classList.remove('was-validated')
                var errMaker = ``
                if (err == false) {
                    errMaker = `<li>User ID atau Password salah</li>`
                    document.querySelector('.alert').innerHTML = errMaker
                } else {
                    Object.keys(err).forEach(item => {
                        err[item].forEach(detail => {
                            errMaker += `<li>${detail}</li>`
                        })
                    })
                    document.querySelector('.alert').innerHTML = errMaker
                    document.querySelector('.alert').style.display = "block"
                }
            },
            complete: () => {
                document.querySelector('.login-wrapper').style.display = "block"
                document.querySelector('svg').style.display = "none"
            }
        })
    })
    $('.text-reset').on('click', function() {
        $('.form-section').html(`
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255); display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" fill="none" stroke="#1d0e0b" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
            </circle>
            <!-- [ldio] generated by https://loading.io/ -->
        </svg>
        `)
        $('title').html("Register")
        $.ajax({
            url: '{{ url("register") }}',
            type: 'GET',
            success: (res) => {
                $('.form-section').html(res)
            },
            error: (err) => {
                $('.form-section').html(err)
            }
        })
    })
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>