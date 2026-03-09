<div class="flex items-center p-6">
    <p class="hcaptcha-disclosure text-sm font-light">
        This site is protected by hCaptcha and its
        <a class="underline" href="https://www.hcaptcha.com/privacy">Privacy Policy</a> and
        <a class="underline" href="https://www.hcaptcha.com/terms">Terms of Service</a> apply.
    </p>
    <script>
        function onHCaptchaDone() {
            document.getElementById("signup-form").submit();
        }
    </script>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</div>
