<template>
    <div v-tooltip:top="{ html: value }">
        <slot></slot>{{ showValue }}
    </div>
</template>
<script>
    module.exports = {
        props: {
            'value': {
                type: String,
                default: '--'
            },
            size: {
                required: false,
                type: Number,
                default: 30
            }
        },
        data: function() {
            return {
                showValue: ''
            };
        },
        mounted () {
            var size = parseInt(this.size);
            this.showValue = (this.value.length > size) ? (this.value.substring(0, size) + '...') : this.value;
        }
    }
</script>