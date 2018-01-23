<template>
    <div>
        <p>ランチに行きたい：<el-switch @change="updateActive" v-model="active"></el-switch></p>
        <br />
        <button @click="createShuffleLunch()">シャッフルランチ</button>
        <h2>ランチ予定</h2>
        <template v-for="lunch in this.$store.state.lunch.lunches">
            <p>予定日時：{{ lunch.lunch_at }}</p>
            <p>参加者</p>
            <template v-for="user in lunch.users">
                <img :src="user.icon_image_url" />
                <p>{{ user.name }}</p>
            </template>
        </template>
    </div>
</template>

<script>
    import api from '../api/index';
    import jwtDecode from 'jwt-decode';

    export default {
        data() {
            return {
                active: false,
            }
        },
        created() {
            this.assignActive();
            this.$store.dispatch('fetchMyLunches');
        },
        methods: {
            assignActive() {
                let payload = jwtDecode(localStorage.getItem('token'));
                api.user.show(payload.sub).then((response) => {
                    this.active = !!response.data.active;
                });
            },
            updateActive() {
                let payload = jwtDecode(localStorage.getItem('token'));
                api.user.update(payload.sub, {active: this.active});
            },
            createShuffleLunch() {
                this.$store.dispatch('createShuffleLunch');
            }
        }
    }
</script>
