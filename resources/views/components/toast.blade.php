<div id="globalToast" class="toast position-fixed top-0 end-0 my-4 mx-3 border-0" role="alert" aria-live="polite" aria-atomic="true"
    data-bs-delay="3000" style="z-index:1999">
    {{-- Header background will be controlled by JS: bg-success or bg-danger --}}
    <div class="toast-header text-white" style="border-bottom: none;">
        <strong class="me-auto"><i class="bi bi-bell-fill"></i> &nbsp; Notification</strong>
        <small>&nbsp;</small>
        {{-- Close button needs to remain white since the header is colored --}}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
    {{-- Body is explicitly set to white background and dark text as requested --}}
    <div class="toast-body bg-white text-dark rounded-bottom shadow-sm">
        <strong class="fw-medium"></strong>
    </div>
</div>