<template>
<div>
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
                <td v-html="deploymentStatusIconOf1[deployment.number]"></td>
                <td style="text-align: right;">{{ deployment.number }}</td>
                <td>{{ deployment.task }}</td>
                <td>{{ deployment.startDate }}</td>
                <td>{{ deployment.finishDate }}</td>
                <td>{{ deploymentUserEmailOf1[deployment.number] }}</td>
                <td v-html="deploymentShowLinkOf1[deployment.number]"></td>
            </tr>
        </tbody>
    </table>
    <div class="text-center" v-html="deploymentPaginationLink1"></div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            deployments: Array,
            deploymentStatusIconOf: Object,
            deploymentUserEmailOf: Object,
            deploymentShowLinkOf: Object,
            deploymentIndexApiUrl: String,
            deploymentPaginationLink: String,
        },
        data: function () {
            return {
                deployments1: this.deployments,
                deploymentStatusIconOf1: this.deploymentStatusIconOf,
                deploymentUserEmailOf1: this.deploymentUserEmailOf,
                deploymentShowLinkOf1: this.deploymentShowLinkOf,
                deploymentPaginationLink1: this.deploymentPaginationLink,
            }
        },
        methods: {
            getDeployments: function () {
                axios.get(this.deploymentIndexApiUrl)
                    .then(res => {
                        this.deployments1 = res.data.deployments;
                        this.deploymentStatusIconOf1 = res.data.deploymentStatusIconOf;
                        this.deploymentUserEmailOf1 = res.data.deploymentUserEmailOf;
                        this.deploymentShowLinkOf1 = res.data.deploymentShowLinkOf;
                        this.deploymentPaginationLink1 = res.data.deploymentPaginationLink;
                    }).catch(error => console.log(error));
            }
        },
        created () {
            setInterval(this.getDeployments, 5000)
        }
    }
</script>
