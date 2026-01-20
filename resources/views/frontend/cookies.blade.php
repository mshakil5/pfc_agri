<style>
    /* --- Modern Cookie Design --- */
    #cookie {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(150%);
        width: 90%;
        max-width: 850px;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        padding: 24px;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    #cookie.active {
        transform: translateX(-50%) translateY(0);
    }

    .cookie-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
        font-weight: 800;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .cookie-text {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 0;
        line-height: 1.5;
    }

    /* --- Button Styling --- */
    .btn-cookie-accept {
        background-color: #007a3d; /* Your brand green */
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .btn-cookie-accept:hover {
        background-color: #1e293b;
        color: white;
    }

    .btn-cookie-reject {
        background-color: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .btn-cookie-reject:hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }

    @media (max-width: 768px) {
        #cookie { bottom: 10px; padding: 20px; }
        .cookie-buttons { width: 100%; display: flex; gap: 10px; }
        .cookie-buttons button { flex: 1; }
    }
</style>

<section id="cookie">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="cookie-title">
                    <i class="fas fa-cookie-bite text-success"></i>
                    Cookie Settings
                </div>
                <p class="cookie-text">
                    We use cookies to enhance your experience and analyze our traffic. By clicking "Accept", you consent to our use of cookies in accordance with our <a href="/privacy-policy" class="text-success text-decoration-none fw-bold">Privacy Policy</a>.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="cookie-buttons d-flex justify-content-lg-end gap-2">
                    <button class="btn-cookie-reject" onclick="rejectCookies()">Decline</button>
                    <button class="btn-cookie-accept" onclick="acceptCookies()">Accept All</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // In Laravel, ensure these scripts are in your main layout or a pushed stack
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
        }
        return null;
    }

    function acceptCookies() {
        setCookie('cookie_consent', 'accepted', 365);
        hideCookieBanner();
        // Trigger analytics scripts here if needed
    }

    function rejectCookies() {
        // Set cookie to 'rejected' so banner doesn't keep appearing
        setCookie('cookie_consent', 'rejected', 30); 
        hideCookieBanner();
    }

    function showCookieBanner() {
        document.getElementById('cookie').classList.add('active');
    }

    function hideCookieBanner() {
        document.getElementById('cookie').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Only show if no preference is saved
        if (!getCookie('cookie_consent')) {
            setTimeout(showCookieBanner, 1500);
        }
    });
</script>