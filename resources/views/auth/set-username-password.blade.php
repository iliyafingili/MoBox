@php $type = 'set-username-password'; @endphp

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تکمیل پروفایل</title>
    @vite(['resources/css/set-username-password.css', 'resources/js/set-username-password.js'])
</head>
<body>
<div class="container">
        <header>
            <img src="/images/logo-mobbox3.png" alt="">
            <h1>موبوکس</h1>
            <h2>تکمیل پروفایل</h2>
        </header>
    <main>
        <form method="POST" action="{{ route('put-username-password')}}">
            @csrf
            <div class="input-group">
                <label for="set-username">لطفا نام کاربری خود را تنظیم کنید:</label>
                <input type="text" name="set-username" value="{{ old('set-username') }}" required
                    pattern="[A-Za-z][A-Za-z0-9]{3,20}"
                    title="نام کاربری باید فقط شامل حروف و اعداد باشد و بین ۳ تا ۲۰ کاراکتر باشد و نمی تواند با عدد شروع شود">
                @error('set-username')
                    <span class="error-forms">{{ $message }}</span>
                @enderror

                <label for="set-password">تنظیم رمز عبور:</label>
                <input type="password" id="set-password" name="set-password" value="{{ old('set-password') }}"
                    required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    title="رمز عبور باید حداقل ۸ کاراکتر باشد و شامل حداقل یک حرف بزرگ، یک حرف کوچک، یک عدد و یک کاراکتر خاص (@$!%*?&) باشد">

                {{-- نمایش قدرت رمز عبور --}}
                <div class="pwd-strength" data-target-input="set-password">
                    <div class="pwd-progress-wrap" aria-hidden="true">
                        <div class="pwd-progress" style="width:0%"></div>
                    </div>
                    <div class="pwd-meta">
                        <span class="pwd-percent">0%</span>
                        <span class="pwd-label">ضعیف</span>
                    </div>
                    <a type="button" id="toggle-rules" class="btn-rules">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                    <div class="pwd-rules" aria-hidden="true">
                        <p id="rule-length" class="rule">❌ حداقل ۸ کاراکتر</p>
                        <p id="rule-lower" class="rule">❌ حداقل یک حرف کوچک</p>
                        <p id="rule-upper" class="rule">❌ حداقل یک حرف بزرگ</p>
                        <p id="rule-number" class="rule">❌ حداقل یک عدد</p>
                        <p id="rule-special" class="rule">❌ حداقل یک کاراکتر خاص (@$!%*?&)</p>
                    </div>
                </div>

                @error('set-password')
                    <span class="error-forms">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-btn">تایید اطلاعات</button>

            <div class="terms">
                ورود شما به معنای پذیرش شرایط و قوانین حریم خصوصی است
            </div>
        </form>
    </main>
</div>
</body>
</html>
