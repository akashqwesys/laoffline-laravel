<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Employee Logs</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{logs.length}} employee logs.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>                                   
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Subject</th>
                                            <th>Log Path</th>
                                            <th>Employee</th>                                            
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(log, index) in logs" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ log.log_subject }}</td>
                                            <td>{{ log.log_path }}</td>
                                            <td>{{ log.firstname }}</td>
                                            <td>{{ dateFormate(log.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'logs',
        data() {
            return {
                logs: [],
            }
        },
        created() {
            axios.get('./logs/list')
            .then(response => {
                this.logs = response.data;
            });
        },
        methods : {
          dateFormate(createdDate) {
            var mydate = new Date(createdDate);
            var month = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"][mydate.getMonth()];
            return mydate.getDate() + '-' + month + '-' + mydate.getFullYear() + " " + mydate.getHours() + ":" + mydate.getMinutes() + ":" + mydate.getSeconds();            
          }
        },
        mounted() {
        },
    };
</script>