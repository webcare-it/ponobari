{{-- Order Data Wrapper Component - Safely displays order data and bypasses errors --}}
@props(['order', 'field', 'fallback' => 'N/A'])

@php
    $value = null;
    $errorOccurred = false;
    
    try {
        // Attempt to get the value using dot notation
        $keys = explode('.', $field);
        $current = $order;
        
        foreach ($keys as $key) {
            if (is_array($current) && isset($current[$key])) {
                $current = $current[$key];
            } elseif (is_object($current) && isset($current->$key)) {
                $current = $current->$key;
            } elseif (is_object($current) && method_exists($current, $key)) {
                $current = $current->$key();
            } else {
                // If we can't access the property, use fallback
                $current = $fallback;
                break;
            }
        }
        
        $value = $current;
    } catch (Exception $e) {
        $errorOccurred = true;
        $value = $fallback;
    }
@endphp

<span @if($errorOccurred) title="Data unavailable due to system error" @endif>
    {{ $value }}
</span>