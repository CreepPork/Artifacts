<template>
    <div>
        <div v-for="(upload, key) in uploads" :key="key">
            <div class="card mb-3 bg-not-so-dark">
                <div class="card-header">
                    Upload #{{ upload.id }}

                    <button type="button" @click="destroy(upload.id, key)" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="card-body">
                    <p>
                        Download <a rel="_blank" :href="'storage/' + upload.filename" class="">{{ upload.original_filename }}</a>.
                    </p>

                    <span class="text-slightly-muted">
                        Created {{ diffForHumans(upload.created_at) }}. {{ upload.created_at !== upload.updated_at ? '| Updated ' + diffForHumans(upload.updated_at) + '.' : '' }}
                    </span>
                </div>
            </div>
        </div>

        <div v-if="uploads.length === 0" class="alert alert-info">
            Nothing to see here! Please wait some more, till something appears here...
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        mounted() {
            this.update();

            Echo.channel('uploads').listen('UploadCreated', event => {
                this.update();

                flash('A new build has been uploaded.');
            });
        },

        data() {
            return {
                uploads: {}
            }
        },

        methods: {
            diffForHumans(date) {
                return moment(date).fromNow();
            },

            update() {
                axios.get('/api/upload').then(response => {
                    this.uploads = response.data;
                })
            },

            destroy(id, key) {
                axios.delete(`/api/upload/${id}`).then(response => {
                    Vue.delete(this.uploads, key);

                    flash('Removed!');
                }).catch(error => {
                    flash(error.response.data.message, 'danger');
                });
            }
        },
    }
</script>
