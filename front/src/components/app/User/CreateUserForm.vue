<template>
    <section>
        <button class="button is-info"
                @click="isComponentModalActive = true, getDepartmentsList()">
            Добавить сотрудника
        </button>

        <b-modal :active.sync="isComponentModalActive"
                 has-modal-card
                 trap-focus
                 :destroy-on-hide="false"
                 aria-role="dialog"
                 aria-modal>
            <form action="" @submit.prevent="addUser">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Добавить сотрудника</p>
                    </header>
                    <section class="modal-card-body">
                        <b-field label="Имя">
                            <b-input
                                    type="text"
                                    rounded
                                    icon="account"
                                    size="is-medium"
                                    :value="formProps.name"
                                    v-model="formProps.name"
                                    required>
                            </b-input>
                        </b-field>

                        <b-field label="Email" :type="{'is-danger': server_error !== ''}" :message="server_error">
                            <b-input
                                    type="email"
                                    rounded
                                    icon="email"
                                    size="is-medium"
                                    :value="formProps.email"
                                    v-model="formProps.email"
                                    @keyup.native="clearServerError()"
                                    required>
                            </b-input>
                        </b-field>

                        <b-field label="Выберите отдел">
                            <b-select
                                    size="is-medium"
                                    icon="account-multiple"
                                    v-model="formProps.department"
                                    rounded
                                    expanded>
                                <option
                                        v-for="department in this.getDepartments"
                                        :key="department.id"
                                        :value="department"
                                >{{department.name}}</option>
                            </b-select>
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

import {mapGetters} from "vuex";

export default {
    name: 'CreateUserForm',
    props: ['email', 'password'],
    data() {
        return {
            isComponentModalActive: false,
            formProps: {
                name: '',
                email: '',
                department: null
            },
            server_error: ''
        }
    },
    computed: {
        ...mapGetters([
            'getDepartments'
        ])
    },
    methods: {
        getDepartmentsList() {
            this.$store.dispatch('retrieveDepartments')
        },
        addUser() {
            this.$store.dispatch('addUser', {
                department_id: this.formProps.department ? this.formProps.department.id : '',
                name: this.formProps.name,
                email: this.formProps.email
            }).then(() => {
                this.isComponentModalActive = false
                this.$store.dispatch('retrieveUsers')
                this.formProps.department = null
                this.formProps.email = ''
                this.formProps.name = ''

                this.$buefy.notification.open({
                    message: 'Сотрудник успешно добавлен!',
                    type: 'is-success',
                    hasIcon: true,
                    position: 'is-top'
                })
            }).catch(error => {
                    if ('failed adding' === error.response.data.result) {
                        this.server_error = 'Что то пошло не так';
                    } else if ('email already taken' === error.response.data.result) {
                        this.server_error = 'Пользователь с таким email уже существует';
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
