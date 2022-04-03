<template>
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

          <b-col cols="12" sm="12" md="6" lg="4" xl="4">
            <div class="form-item">
              <label for="filter-level">{{ $t('CLASSES.LABEL_FILTER_LEVEL') }}</label>
              <b-form-select
                id="filter-level"
                v-model="isFilter.level"
                :options="listLevel"
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
                <span>{{ $t('ACCOUNT.BUTTON_ADD_NEW') }}</span>
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
          <b-form-input id="form-name" v-model="isClass.name" :placeholder="$t('CLASSES.PLACEHOLDER_FORM_NAME')" />
        </div>

        <div class="item-input">
          <label for="form-level">{{ $t('CLASSES.PLACEHOLDER_FORM_LEVEL') }}</label>
          <b-form-select
            id="form-level"
            v-model="isClass.level"
            :options="listLevel"
          />
        </div>
      </template>

      <template #modal-footer>
        <b-button variant="outline-danger" @click="onClickCancelModalForm()">
          {{ $t('CLASSES.BUTTON_CANCEL') }}
        </b-button>
        <b-button class="btn-custom-green" @click="onClickSumbitModalForm()">
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
</template>

<script>
const ACITON_ADD = 'ADD';
const ACTION_UPDATE = 'UPDATE';

export default {
  name: 'Classes',
  data() {
    return {
      isClass: {
        name: '',
        level: null,
      },

      isFilter: {
        name: '',
        level: null,
      },

      listLevel: [
        {
          value: null,
          text: this.$t('CLASSES.PLACEHOLDER_FILTER_LEVEL'),
        },
        {
          value: 1,
          text: 'Level 1',
        },
        {
          value: 2,
          text: 'Level 2',
        },
      ],

      items: [
        {
          id: 1,
          name: 'Chemistry',
          level: 1,
        },
        {
          id: 2,
          name: 'Physics',
          level: 1,
        },
      ],

      pagination: {
        page: 1,
        perPage: 20,
        total: 1,
      },

      visibleModalForm: false,
      visibleModalDelete: false,
      isAction: '',
      idHandle: null,
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
          key: 'level',
          label: this.$t('CLASSES.TABLE_TITLE_LEVEL'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
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
  methods: {
    showModalForm() {
      this.visibleModalForm = true;
    },
    hideModalForm() {
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
    onClickUpdate(id) {
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
    onClickSumbitModalForm() {
      this.hideModalForm();
    },
    onClickCancelModalDelete() {
      this.hidenModalDelete();
    },
    onClickSubmitModalDelete() {
      this.hidenModalDelete();
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@/scss/_variables";

.classes {
    padding: 10px;

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
</style>
