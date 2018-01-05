<template>
    <div>
        <button @click="createShuffleLunch()">シャッフルランチ</button>
        <h2>ランチ予定</h2>
        <template v-for="lunch in lunches">
            <p>予定日時：{{ lunch.lunch_at }}</p>
            <p>参加者</p>
            <template v-for="user in lunch.users">
                <p>{{ user.name }}</p>
            </template>
        </template>
    </div>
</template>

<script>
    import api from '../api/index';

    export default {
        data() {
            return {
                lunches: []
            }
        },
        mounted() {
            this.fetchMyLunches();
        },
        methods: {
            fetchMyLunches() {
                api.lunch.list().then((response) => {
                    this.lunches = response.data;
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
