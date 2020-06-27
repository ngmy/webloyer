<template>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Number</th>
                        <td>{{ number }}</td>
                    </tr>
                    <tr>
                        <th>Task</th>
                        <td>{{ task }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ status1 }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td><pre class="ansi_box"><code v-html="log1"></code></pre></td>
                    </tr>
                    <tr>
                        <th>Started At</th>
                        <td>{{ startedAt1 }}</td>
                    </tr>
                    <tr>
                        <th>Finished At</th>
                        <td>{{ finishedAt1 }}</td>
                    </tr>
                    <tr>
                        <th>Executed By</th>
                        <td>{{ executedBy }}</td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-danger" v-bind:href="deploymentIndexLink">Back</a>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            number: String,
            task: String,
            status: String,
            log: String,
            startedAt: String,
            finishedAt: String,
            executedBy: String,
            deploymentShowApiUrl: String,
            deploymentIndexLink: String,
        },
        data: function () {
            return {
                status1: this.status,
                log1: this.log,
                startedAt1: this.startedAt,
                finishedAt1: this.finishedAt,
            }
        },
        methods: {
            getDeployment: function () {
                if (
                    this.status1 == 'succeeded' ||
                    this.status1 == 'failed'
                ) {
                    return;
                }
                axios.get(this.deploymentShowApiUrl)
                    .then(res => {
                        this.status1 = res.data.deployment.status;
                        this.log1 = res.data.deploymentLog;
                        this.startedAt1 = res.data.deployment.startDate;
                        this.finishedAt1 = res.data.deployment.finishDate;
                    }).catch(error => console.log(error));
            }
        },
        created () {
            setInterval(this.getDeployment, 5000)
        }
    }
</script>
