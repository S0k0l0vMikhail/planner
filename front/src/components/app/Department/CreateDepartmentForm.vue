<template>
    <section>
        <button class="button is-info"
                @click="isComponentModalActive = true">
            Добавить отдел
        </button>

        <b-modal :active.sync="isComponentModalActive"
                 has-modal-card
                 trap-focus
                 :destroy-on-hide="false"
                 aria-role="dialog"
                 aria-modal>
            <form action="" @submit.prevent="addDepartment">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Добавить отдел</p>
                    </header>
                    <section class="modal-card-body">
                        <b-field label="Название" :type="{'is-danger': server_error !== ''}" :message="server_error">
                            <b-input
                                    type="text"
                                    rounded
                                    icon="account-multiple"
                                    size="is-medium"
                                    :value="name"
                                    v-model="name"
                                    required>
                            </b-input>
                        </b-field>
                    </section>
                    <footer class="modal-card-foot">
                        <button class="button" type="button" @click="isComponentModalActive = false">Отмена</button>
                        <button class="button is-primary" native-type="submit">Добавить</button>
                    </footer>
                </div>
            </form>
        </b-modal>
    </section>
</template>

<script>
    export default {
        name: 'CreateDepartmentForm',
        data() {
            return {
                isComponentModalActive: false,
                name: '',
                server_error: ''
            }
        },
        methods: {
            addDepartment() {
                this.$store.dispatch('addDepartment', {
                    name: this.name
                }).then(() => {
                    this.isComponentModalActive = false
                    this.$store.dispatch('retrieveDepartments')
                    this.name = ''

                    this.$buefy.notification.open({
                        message: 'Отдел успешно добавлен!',
                        type: 'is-success',
                        hasIcon: true,
                        position: 'is-top'
                    })
                }).catch(error => {
                    if ('failed adding' === error.response.data.result) {
                        this.server_error = 'Что то пошло не так';
                    } else if ('name already taken' === error.response.data.result) {
                        this.server_error = 'Отдел с таким названием уже существует';
                    } else {
                        this.server_error = error.response.data.result
                    }
                })
            },
            clearServerError() {
                this.server_error = ''
            }
        }
    }
</script>
