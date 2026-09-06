{{-- Error Bypass Component for Admin Order Blades --}}
@php
    // This component bypasses errors in admin order blades
    // It catches exceptions and displays a fallback UI instead of breaking the page
@endphp

@if(Session::has('admin_order_error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Notice:</strong> Some order data could not be loaded properly. 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<script>
    // Global error handler for admin order pages
    window.onerror = function(message, source, lineno, colno, error) {
        // Check if we're on an admin order page
        if (window.location.href.includes('/admin/') && 
            (window.location.href.includes('order') || window.location.href.includes('customer'))) {
            
            // Prevent error from breaking the page
            console.error('Bypassed error in admin order page:', message);
            
            // Show a subtle notification to admins
            if (typeof Lobibox !== 'undefined') {
                Lobibox.notify('warning', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-error',
                    msg: 'Some order data could not be loaded. Page continued loading.',
                    size: 'mini'
                });
            }
            
            // Return true to prevent browser's default error handling
            return true;
        }
        
        // For non-admin order pages, let the default error handling occur
        return false;
    };
    
    // Handle Promise rejections
    window.addEventListener('unhandledrejection', function(event) {
        // Check if we're on an admin order page
        if (window.location.href.includes('/admin/') && 
            (window.location.href.includes('order') || window.location.href.includes('customer'))) {
            
            // Prevent error from breaking the page
            console.error('Bypassed promise rejection in admin order page:', event.reason);
            
            // Don't prevent the default behavior for promises, but log it
            // Returning false would prevent the default behavior
        }
    });
</script>