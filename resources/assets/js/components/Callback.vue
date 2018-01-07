<template>
    <div>
    </div>
</template>

<script>
    import api from '../api/index';

    export default {
        created() {
            const {code, state} = this.$route.query;
            api.user.login(code, state).then((response) => {
                localStorage.token = response.data.token;
                this.$router.push('/list');
            }).catch(e => {
                console.log(e);
            });
        }
    }
</script>
