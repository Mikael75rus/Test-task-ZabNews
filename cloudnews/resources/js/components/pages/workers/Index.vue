<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
            <!--      Поле с ошибками валидации          -->
                <validation-errors v-if="validationErrors"
                                    :errors="validationErrors"></validation-errors>
            <!--       Форма для добавления и редактирования записей         -->
                <form @submit.prevent="addWorker" class="mb-4">
                    <div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input v-model="worker.name" type="text" class="form-control" id="name">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Дата рождения</label>
                            <input v-model="worker.date" type="date" class="form-control" id="date">
                        </div>
                        <div class="mb-3">
                            <label for="post" class="form-label">Должность</label>
                            <input v-model="worker.post" type="text" class="form-control" id="post">
                        </div>
                    </div>
            <!--         При редактировании кнопка "добавить" меняется на "сохранить"           -->
                    <button v-if="edit" type="submit" class="btn btn-primary">Сохранить</button>
                    <button v-else type="submit" class="btn btn-primary">Добавить</button>
            <!--        При редактировании записи появляется кнопка "отмена"            -->
                    <button v-if="edit" @click="cancelEditWorker()" type="button" class="btn ms-xl-4 btn-light">Отмена</button>
                </form>
            </div>
        </div>

    <!--    Отображение ошибки, если записи не загрузились    -->
        <div v-if="errored" class="alert alert-danger" role="alert">
            Записи не загрузились, попробуйте позже
        </div>

    <!--    Индикатор загрузки и отображение таблицы данных   -->
        <table v-else class="table table-striped">
            <div v-if="loading">Загрузка...</div>
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Имя</th>
                <th scope="col">Дата рождения</th>
                <th scope="col">Должность</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
    <!--    Цикл для вывода записей        -->
            <tr v-for="worker in workers" :key="worker.id">
                <th>{{ worker.id }}</th>
                <td>{{ worker.name }}</td>
                <td>{{ worker.date }}</td>
                <td>{{ worker.post }}</td>
    <!--      Кнопки для удаления и редактирования записи          -->
                <td>
                    <button @click="editWorker(worker)" class="btn btn-success">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button @click="deleteWorker(worker.id)" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

    <!--    Пагинация (10 записей на страницу)   -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li :class="{ disabled: !pagination.prev_page_url }"
                    @click.p.prevent="getWorkers(pagination.prev_page_url)"
                    class="page-item">
                    <a class="page-link" href="#">Назад</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#">
                        Страница {{ pagination.current_page }} из {{pagination.last_page}}
                    </a>
                </li>
                <li :class="{ disabled: !pagination.next_page_url }"
                    @click.p.prevent="getWorkers(pagination.next_page_url)"
                    class="page-item">
                    <a class="page-link" href="#">Следующая</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            workers: [],
            worker: {
                id: '',
                name: '',
                date: '',
                post: '',
            },
            pagination: {}, // Объект, который содержит информацию о текущей, последней, следующей и предыдущей странице
            edit: false,    // Свойство, которое определяет редактируется ли запись
            loading: true,  // Индикатор загрузки
            errored: false, // Ошибка при выводе записей
            validationErrors: '', // Массив ошибок валидации
            add: false,     // Свойство, которое определяет показывать ли форму для ввода данных
        }
    },
    mounted() {
        this.getWorkers()
    },

    methods: {
        // Вывод записей содержащихся на данной странице
        getWorkers (page_url) {
            page_url = page_url || '/api/workers'
            axios
                .get(page_url)
                .then(response => {
                    this.workers = response.data.data;
                    this.makePagination(response.data);
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => this.loading = false)
        },

        makePagination(response) {
            this.pagination = {
                current_page: response.current_page,
                last_page: response.last_page,
                prev_page_url: response.prev_page_url,
                next_page_url: response.next_page_url,
            };
        },
        addWorker() {
            // Добавление записи в БД, если запись не редактируется
            if (this.edit === false) {
                axios
                    .post('/api/workers', {
                        name: this.worker.name,
                        date: this.worker.date,
                        post: this.worker.post,
                    })
                    .then(response => {
                        this.worker.name = '';
                        this.worker.date = '';
                        this.worker.post = '';
                        this.getWorkers();
                        this.validationErrors = '';
                    })
                    .catch(error => {
                        if(error.response.status === 422){
                            this.validationErrors = error.response.data.errors;
                        }
                        console.log(error);
                    })
            } else {    // Редактирование записи и отправка в БД
                axios
                    .put(`/api/workers/${this.worker.id}`, {
                        name: this.worker.name,
                        date: this.worker.date,
                        post: this.worker.post,
                    })
                    .then(response => {
                        this.worker.name = '';
                        this.worker.date = '';
                        this.worker.post = '';
                        this.edit = false;
                        this.getWorkers();
                        this.validationErrors = '';
                    })
                    .catch(error => {
                        if(error.response.status === 422){
                            this.validationErrors = error.response.data.errors;
                        }
                        console.log(error);
                    })
            }
        },
        deleteWorker(id) {
            axios
                .delete(`/api/workers/${id}`)
                .then(response => {
                    this.getWorkers();
                })
                .catch(error => {
                    console.log(error);
                })
        },
        // Редактирование записи на фронтенде
        editWorker(worker){
            this.edit = true;
            this.worker.id = worker.id;
            this.worker.name = worker.name;
            this.worker.date = worker.date;
            this.worker.post = worker.post;
        },
        // Метод для очистки формы добавления/редактирования и отмены edit=true
        cancelEditWorker(){
            this.edit = false;
            this.worker.name = '';
            this.worker.date = '';
            this.worker.post = '';
        }
    }

}
</script>
