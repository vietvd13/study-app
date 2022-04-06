<template>
  <b-overlay
    :show="overlay.show"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
    :blur="overlay.blur"
    :rounded="overlay.sm"
  >
    <template #overlay>
      <div class="text-center">
        <i class="fad fa-spinner-third fa-spin icon-loading" />
        <p style="margin-top: 10px;">{{ $t('LOADING_TEXT') }}</p>
      </div>
    </template>
    <div class="classes">
      <div class="classes__header">
        <b-card>
          <b-row>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="form-item">
                <label for="filter-name">{{ $t('CLASSES.LABEL_FILTER_NAME') }}</label>
                <b-form-input
                  id="filter-name"
                  v-model="isFilter.name"
                  :placeholder="$t('CLASSES.PLACEHOLDER_FILTER_NAME')"
                />
              </div>
            </b-col>
          </b-row>
        </b-card>
      </div>

      <div class="classes__content">
        <b-card>
          <b-row>
            <b-col>
              <div class="d-flex justify-content-end classes__content__add">
                <b-button class="btn-custom-green" @click="onClickAdd()">
                  <i class="fas fa-plus-circle" />
                  <span>{{ $t('CLASSES.BUTTON_ADD_NEW') }}</span>
                </b-button>
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-table
                id="table-classes"
                bordered
                striped
                responsive
                no-sort-reset
                no-local-sorting
                show-empty
                :fields="fields"
                :items="items"
                :total-rows="pagination.total"
                :per-page="pagination.perPage"
                aria-controls="table-classes"
              >
                <template #cell(actions)="data">
                  <div class="td-actions">
                    <div class="actions-edit">
                      <b-button variant="warning" size="sm" @click="onClickUpdate(data.item.id)">
                        <i class="fas fa-pencil-alt" />
                      </b-button>
                    </div>
                    <div class="actions-delete">
                      <b-button variant="danger" size="sm" @click="onClickDelete(data.item.id)">
                        <i class="fas fa-trash" />
                      </b-button>
                    </div>
                  </div>
                </template>
                <template #empty>
                  <span class="d-flex justify-content-center">
                    {{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}
                  </span>
                </template>
              </b-table>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-pagination
                v-model="pagination.page"
                pills
                size="sm"
                first-number
                last-number
                align="right"
                :total-rows="pagination.total"
                :per-page="pagination.perPage"
                aria-controls="table-classes"
              />
            </b-col>
          </b-row>
        </b-card>
      </div>

      <b-modal
        v-model="visibleModalForm"
        size="lg"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-classes-content"
        footer-class="modal-classes-footer"
      >
        <template #modal-header>
          <h5 v-if="isAction === 'ADD'">{{ $t('CLASSES.MODAL_TITLE_ADD') }}</h5>
          <h5 v-if="isAction === 'UPDATE'">{{ $t('CLASSES.MODAL_TITLE_UPDATE') }}</h5>
        </template>

        <template #default>
          <div class="item-input">
            <label for="form-name">{{ $t('CLASSES.LABEL_FORM_NAME') }}</label>
            <b-form-input id="form-name" v-model="isClass.name" :disabled="isProcess" :placeholder="$t('CLASSES.PLACEHOLDER_FORM_NAME')" />
          </div>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCancelModalForm()">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>
          <b-button class="btn-custom-green" :disabled="isProcess" @click="onClickSumbitModalForm()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalDelete"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-delete-content"
        footer-class="modal-delete-footer"
      >
        <template #modal-header>
          <h5>{{ $t('CLASSES.MODAL_TITLE_DELETE') }}</h5>
        </template>

        <template #default>
          <p>{{ $t('CLASSES.CONTENT_MODAL_DELETE') }}</p>
        </template>

        <template #modal-footer>
          <b-button class="btn-custom-outline-charade" @click="onClickCancelModalDelete()">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" @click="onClickSubmitModalDelete()">
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
const ACITON_ADD = 'ADD';
const ACTION_UPDATE = 'UPDATE';

const URL_API = {
  getAll: '/classes',
  getOne: '/classes',
  postClasses: '/classes',
  putClasses: '/classes',
  deleteClasses: '/classes',
};
import {
  getAllClasses,
  getOneClasses,
  postClasses,
  putClasses,
  deleteClasses,
} from '@/api/modules/classes';

import {
  validateAddClasses,
  validateUpdateClasses,
} from './validate';

import NotifyClasses from '@/toast/modules/classes';

export default {
  name: 'Classes',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      isClass: {
        name: '',
        level: 1,
      },

      isFilter: {
        name: '',
      },

      items: [],

      pagination: {
        page: 1,
        perPage: 10,
        total: 0,
      },

      visibleModalForm: false,
      visibleModalDelete: false,
      isAction: '',
      idHandle: null,
      isProcess: false,
    };
  },
  computed: {
    fields() {
      return [
        {
          key: 'name',
          label: this.$t('CLASSES.TABLE_TITLE_NAME'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
            key: 'arrangement'
        },
        {
          key: 'actions',
          lebael: this.$t('CLASSES.TABLE_TITLE_ACTIONS'),
          thClass: 'base-th base-actions',
          tdClass: 'base-td base-actions',
        },
      ];
    },
  },
  created() {
    this.initData();
  },
  methods: {
    async initData() {
      this.overlay.show = true;
      await this.handleGetAllClasses();
      this.overlay.show = false;
    },
    async handleGetAllClasses() {
      const URL = URL_API['getAll'];

      const PARAMS = {
        page: this.pagination.page,
        per_page: this.pagination.perPage,
      };

      try {
        const res = await getAllClasses(URL, PARAMS);

        if (res['status'] === 200) {
          this.items = res['data'];
          this.pagination.page = res['per_page'];
        } else {
          NotifyClasses.server(res['message']);
        }
      } catch {
        NotifyClasses.exception();
      }
    },
    async handleGetOneClasses(id) {
      const URL = `${URL_API['getOne']}/${id}`;

      try {
        const res = await getOneClasses(URL);

        if (res['status'] === 200) {
          this.isClass.name = res['data']['name'];
        } else {
          NotifyClasses.server(res['message']);
        }
      } catch {
        NotifyClasses.exception();
      }
    },
    async handleAddClasses() {
      this.isProcess = true;
      const URL = URL_API.postClasses;

      const DATA = {
        name: this.isClass.name,
        level: this.isClass.level,
      };

      const validate = validateAddClasses(DATA);

      if (validate.status) {
        try {
          const res = await postClasses(URL, DATA);

          if (res['status'] === 200) {
            this.isProcess = false;
            this.hideModalForm();
            NotifyClasses.addSuccess(res.data.name);
            this.initData();
          } else {
            this.isProcess = true;
            NotifyClasses.server(res['message']);
          }
        } catch (error) {
          NotifyClasses.addError(error);
          this.isProcess = false;
        }
      } else {
        NotifyClasses.validateForm(`NOTIFY.CLASSES.${validate.message.shift()}`);
        this.isProcess = false;
      }
    },
    async handleUpdateClasses(id) {
      this.isProcess = true;
      const URL = `${URL_API.putClasses}/${id}`;

      const DATA = {
        name: this.isClass.name,
      };

      const validate = validateUpdateClasses(DATA);

      if (validate.status) {
        try {
          const res = await putClasses(URL, DATA);

          if (res['status'] === 200) {
            this.isProcess = false;
            this.hideModalForm();
            NotifyClasses.updateSuccess(res.data.name);
            this.initData();
          } else {
            NotifyClasses.server(res['message']);
          }
        } catch (error) {
          NotifyClasses.updateError(error);
          this.isProcess = false;
        }
      } else {
        NotifyClasses.validateForm(`NOTIFY.CLASSES.${validate.message.shift()}`);
        this.isProcess = false;
      }
    },
    async handleDeleteClasses(id) {
      const URL = `${URL_API.deleteClasses}/${id}`;

      try {
        const res = await deleteClasses(URL);

        if (res['status'] === 200) {
          this.hidenModalDelete();
          NotifyClasses.deleteSuccess();
          this.initData();
        } else {
          NotifyClasses.server(res['message']);
        }
      } catch {
        NotifyClasses.exception();
      }
    },
    resetModalForm() {
      const DEFAULT = {
        name: '',
        level: 1,
      };

      this.isClass = DEFAULT;
    },
    showModalForm() {
      this.visibleModalForm = true;
    },
    hideModalForm() {
      this.resetModalForm();
      this.visibleModalForm = false;
    },
    showModalDelete() {
      this.visibleModalDelete = true;
    },
    hidenModalDelete() {
      this.visibleModalDelete = false;
    },
    onClickAdd() {
      this.isAction = ACITON_ADD;
      this.showModalForm();
    },
    async onClickUpdate(id) {
      await this.handleGetOneClasses(id);
      this.isAction = ACTION_UPDATE;
      this.idHandle = id;
      this.showModalForm();
    },
    onClickDelete(id) {
      this.idHandle = id;
      this.showModalDelete();
    },
    onClickCancelModalForm() {
      this.hideModalForm();
    },
    async onClickSumbitModalForm() {
      if (this.isAction === ACITON_ADD) {
        await this.handleAddClasses();
      }

      if (this.isAction === ACTION_UPDATE) {
        await this.handleUpdateClasses(this.idHandle);
      }
    },
    onClickCancelModalDelete() {
      this.hidenModalDelete();
    },
    async onClickSubmitModalDelete() {
      await this.handleDeleteClasses(this.idHandle);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@/scss/_variables";

.classes {
    padding: 10px;
    height: calc(100vh - 57px);

    &__header {
        margin-bottom: 10px;

        .form-item {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    }

    &__content {
        margin-bottom: 10px;

        &__add {
            margin-bottom: 10px;

            button {
                min-width: 150px;

                i {
                    margin-right: 10px;
                }
            }
        }

        ::v-deep table#table-classes {
            thead {
                tr {
                    th.base-th {
                        min-width: 130px;
                        background-color: $charade;
                        color: $white;
                        text-align: center;
                    }
                }
            }

            tbody {
                tr {
                    td.base-td {
                        text-align: center;
                    }

                    td.base-td.base-actions {
                        width: 200px;
                    }

                    td {
                        .td-actions {
                            display: flex;

                            .actions-edit {
                                width: 50%;

                                i {
                                    color: $white;
                                }
                            }

                            .actions-delete {
                                width: 50%;

                                i {
                                    color: $white;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.modal-classes-content {
    .item-input {
        margin-bottom: 10px;
    }
}

.icon-loading {
    font-size: 50px;
    color: $forest-green;
}
</style>
