<template>
    <div class="container mt-3" v-if="message">
        <div :class="alertClass" role="alert">
            <strong>{{ title }}</strong> {{ message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="clearMessage"></button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: '',
            title: '',
            type: 'success'
        }
    },
    
    computed: {
        alertClass() {
            const baseClass = 'alert alert-dismissible fade show';
            const typeClass = this.type === 'error' ? 'alert-danger' : 'alert-success';
            return `${baseClass} ${typeClass}`;
        }
    },
    
    methods: {
        clearMessage() {
            this.message = '';
            this.title = '';
        }
    },
    
    mounted() {
        // Listen for flash events
        window.flash = (message, type = 'success') => {
            this.message = message;
            this.title = type === 'error' ? 'Error!' : 'Success!';
            this.type = type;
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                this.clearMessage();
            }, 5000);
        };
    }
}
</script>