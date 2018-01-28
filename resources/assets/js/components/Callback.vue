<template>
    <div>
    </div>
</template>

<script>
    export default {
        created() {
            const {code, state} = this.$route.query;
            this.$store.dispatch('login', {code, state}).then((result) => {
                if (result === 200) {
                    this.$router.push('/list');
                }
            });
        }
    }
</script>
