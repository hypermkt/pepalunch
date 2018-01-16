<template>
    <div>
        <p>ランチに行きたい：<el-switch v-model="active"></el-switch></p>
        <br />
        <button @click="createShuffleLunch()">シャッフルランチ</button>
        <h2>ランチ予定</h2>
        <template v-for="lunch in lunches">
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
                user: {},
                lunches: []
            }
        },
        computed: {
            active() {
                return !!this.user.active;
            }
        },
        mounted() {
            this.fetchUser();
            this.fetchMyLunches();
        },
        methods: {
            fetchMyLunches() {
                api.lunch.list().then((response) => {
                    this.lunches = response.data;
                });
            },
            fetchUser() {
                let payload = jwtDecode(localStorage.getItem('token'));
                api.user.show(payload.sub).then((response) => {
                    this.user = response.data;
                });
            },
            createShuffleLunch() {
                api.lunch.create().then((response) => {
                    this.fetchMyLunches();
                });
            }
        }
    }
</script>
