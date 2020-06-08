<template>
<table class="table table-striped">
    <thead>
        <tr>
            <th><div align="center">Status</div></th>
            <th><div align="center">Number</div></th>
            <th><div align="center">Task</div></th>
            <th><div align="center">Started At</div></th>
            <th><div align="center">Finished At</div></th>
            <th><div align="center">Executed By</div></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="deployment in deployments1">
            <td v-html="deploymentStatus[deployment.status]"></td>
            <td style="text-align: right;">{{ deployment.number }}</td>
            <td>{{ deployment.task }}</td>
            <td>{{ deployment.startDate }}</td>
            <td>{{ deployment.finishDate }}</td>
            <td>{{ deployment.user.email }}</td>
            <td v-html="deploymentLinks[deployment.number]"></td>
        </tr>
    </tbody>
</table>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            deployments: Array,
            deploymentStatus: Object,
            deploymentLinks: Object,
            deploymentApiUrls: Object,
        },
        data: function () {
            return {
                deployments1: this.deployments,
            }
        },
        methods: {
            getDeployments: function () {
                for (const i in this.deployments1) {
                    if (
                        this.deployments1[i].status == 'succeeded' ||
                        this.deployments1[i].status == 'failed'
                    ) {
                        continue;
                    }
                    axios.get(this.deploymentApiUrls[this.deployments1[i].number])
                        .then(res => {
                            const newDeployment = Object.assign(this.deployments[i], {
                                status: res.data.deployment.status,
                                log: res.data.deploymentLog,
                                startDate: res.data.deployment.startDate,
                                finishDate: res.data.deployment.finishDate,
                            });
                            this.$set(this.deployments1, i, newDeployment);
                    }).catch(error => console.log(error));
                }
            }
        },
        created () {
            setInterval(this.getDeployments, 3000)
        }
    }
</script>
