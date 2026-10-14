
@if (session('error'))
    <div class="middleware-alert" role="alert">
        <i class="bx bx-lock-alt"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<style>
.middleware-alert {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 24px;
    color: #fff;
    background: #dc3545;
    border-radius: 9px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, .15);
    font-size: 15px;
    font-weight: 500;
    animation: notification-in .25s ease-out;
}

@keyframes notification-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
