@php $type = 'login'; @endphp

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>ورود</title>
    @vite(['resources/css/login.css', 'resources/js/login.js'])
    @vite(['resources/css/svg.css', 'resources/js/svg.js'])

</head>

<body>
    {{-- <div class="container-wrapper"> --}}
    <div class="container">
        <header>
            <img src="/images/logo-mobbox3.png" alt="">
            <h1>موباکس</h1>
            <h2>ورود</h2>
        </header>

        <main>
            <form method="POST" action="{{ route('Resumelogin') }}">
                @csrf
                <div class="input-group">
                    <label for="username">لطفا نام کاربری خود را وارد کنید:</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                        pattern="[A-Za-z][A-Za-z0-9]{3,20}"
                        title="نام کاربری باید فقط شامل حروف و اعداد باشد و بین ۳ تا ۲۰ کاراکتر باشد و نمی تواند با عدد شروع شود">
                    @error('username')
                        <span class="error-forms">{{ $message }}</span>
                    @enderror

                    <label for="password">رمز عبور:</label>
                    <input type="password" name="password" value="{{ old('password') }}" required id="password"
                        title="رمز عبور باید حداقل ۸ کاراکتر باشد و شامل حداقل یک حرف بزرگ، یک حرف کوچک، یک عدد و یک کاراکتر خاص (@$!%*?&) باشد">
                    @error('password')
                        <span class="error-forms">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">ورود</button>

                <div class="register-forgetpass">
                    <a href="{{ route('register') }}">ثبت‌نام</a>
                    <a href="{{ route('forgot') }}">فراموشی رمز عبور</a>
                </div>

                <div class="terms">
                    ورود شما به معنای پذیرش شرایط و قوانین حریم خصوصی است
                </div>

                <input type="hidden" name="intended" value="{{ request('intended') }}">
            </form>

        </main>

    </div>
    @php
        $isMobile = preg_match(
            '/(android|iphone|ipad|ipod|blackberry|phone|mobile|opera mini|iemobile)/i',
            request()->header('User-Agent'),
        );
    @endphp

    @if (!$isMobile)
        <section>
            <x-svg></x-svg>
        </section>
    @endif


</body>

</html>
