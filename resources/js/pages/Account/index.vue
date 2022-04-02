<template>
  <div class="account">
    <div class="account__header">
      <b-card>
        <b-row>
          <b-col cols="12" sm="12" md="6" lg="6" xl="3">
            <div class="form-item">
              <label for="filter-fullname">{{
                $t('ACCOUNT.LABEL_FILTER_FULLNAME')
              }}</label>
              <b-form-input
                id="filter-fullname"
                v-model="isFilter.fullname"
                :placeholder="$t('ACCOUNT.PLACEHOLDER_FILTER_FULLNAME')"
              />
            </div>
          </b-col>

          <b-col cols="12" sm="12" md="6" lg="6" xl="3">
            <div class="form-item">
              <label for="filter-role">{{ $t('ACCOUNT.LABEL_FILTER_ROLE') }}</label>
              <b-form-select
                id="filter-role"
                v-model="isFilter.role"
                :options="listRole"
              />
            </div>
          </b-col>

          <b-col cols="12" sm="12" md="6" lg="6" xl="3">
            <div class="form-item">
              <label for="filter-phone">{{
                $t('ACCOUNT.LABEL_FILTER_TELEPHONE')
              }}</label>
              <b-form-input
                id="filter-phone"
                v-model="isFilter.phone"
                :placeholder="$t('ACCOUNT.PLACEHOLDER_FILTER_TELEPHONE')"
              />
            </div>
          </b-col>

          <b-col cols="12" sm="12" md="6" lg="6" xl="3">
            <div class="form-item">
              <label for="filter-email">{{ $t('ACCOUNT.LABEL_FILTER_EMAIL') }}</label>
              <b-form-input
                id="filter-email"
                v-model="isFilter.email"
                :placeholder="$t('ACCOUNT.PLACEHOLDER_FILTER_EMAIL')"
              />
            </div>
          </b-col>
        </b-row>
      </b-card>
    </div>

    <div class="account__content">
      <b-card>
        <b-row>
          <b-col>
            <div class="d-flex justify-content-end account__content__add">
              <b-button class="btn-custom" @click="onClickAdd()">
                <i class="fas fa-plus-circle" />
                <span>{{ $t('ACCOUNT.BUTTON_ADD_NEW') }}</span>
              </b-button>
            </div>
          </b-col>
        </b-row>

        <b-row>
          <b-col>
            <b-table
              id="table-account"
              bordered
              striped
              responsive
              :no-sort-reset="true"
              no-local-sorting
              show-empty
              :fields="fields"
              :items="items"
              :per-page="pagination.perPage"
              :current-page="pagination.page"
            >
              <template #cell(status)="data">
                <b-badge v-if="data.item.status === 1" variant="success">
                  {{ $t('ACCOUNT.TABLE_CONTENT_STATUS_ACTIVE') }}
                </b-badge>
                <b-badge v-if="data.item.status === 0" variant="danger">
                  {{ $t('ACCOUNT.TABLE_CONTENT_STATUS_INACTIVE') }}
                </b-badge>
              </template>

              <template #cell(actions)="data">
                <div class="td-actions">
                  <div class="actions-edit">
                    <b-button
                      variant="warning"
                      size="sm"
                      @click="onClickUpdate(data.item.id)"
                    >
                      <i class="fas fa-pencil-alt" />
                    </b-button>
                  </div>

                  <div class="actions-delete">
                    <b-button
                      variant="danger"
                      size="sm"
                      @click="onClickDelete(data.item.id)"
                    >
                      <i class="fas fa-trash" />
                    </b-button>
                  </div>
                </div>
              </template>

              <template #empty>
                <span class="d-flex justify-content-center">{{ $t('ACCOUNT.TABLE_CONTENT_NO_DATA') }}</span>
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
              aria-controls="table-account"
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
      body-class="modal-account-content"
      footer-class="modal-account-footer"
    >
      <template #modal-header>
        <div class="modal-account-header">
          <h5 v-if="isAction === 'ADD'">{{ $t('ACCOUNT.MODAL_TITLE_ADD') }}</h5>
          <h5 v-if="isAction === 'UPDATE'">{{ $t('ACCOUNT.MODAL_TITLE_UPDATE') }}</h5>
        </div>
      </template>

      <template #default>
        <div class="item-input">
          <label for="form-fullname">{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}</label>
          <b-form-input id="form-fullname" v-model="isUser.fullname" />
        </div>

        <div class="item-input">
          <label for="form-code">{{ $t('ACCOUNT.LABEL_FORM_CODE') }}</label>
          <b-form-input id="form-code" v-model="isUser.code" />
        </div>

        <div class="item-input">
          <label for="form-telephone">{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}</label>
          <b-form-input id="form-telephone" v-model="isUser.telephone" />
        </div>

        <div class="item-input">
          <label for="form-email">{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}</label>
          <b-form-input id="form-email" v-model="isUser.email" />
        </div>

        <div class="item-input">
          <label for="form-role">{{ $t('ACCOUNT.LABEL_FORM_ROLE') }}</label>
          <b-form-select id="form-role" v-model="isUser.role" :options="listRole" />
        </div>

        <div class="item-input">
          <label for="form-password">{{ $t('ACCOUNT.LABEL_FORM_PASSWORD') }}</label>
          <b-form-input id="form-password" v-model="isUser.password" />
        </div>

        <div class="item-input">
          <b-form-checkbox id="form-blind" v-model="isUser.blind" name="blind">
            {{ $t('ACCOUNT.LABEL_FORM_BLIND') }}
          </b-form-checkbox>
        </div>
      </template>

      <template #modal-footer>
        <b-button variant="outline-danger" @click="onClickCancelModalForm()">{{
          $t('ACCOUNT.BUTTON_CANCEL')
        }}</b-button>
        <b-button class="btn-custom" @click="onClickSumbitModalForm()">{{
          $t('ACCOUNT.BUTTON_SUBMIT')
        }}</b-button>
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
        <h5>{{ $t('ACCOUNT.MODAL_TITLE_DELETE') }}</h5>
      </template>

      <template #default>
        <p>{{ $t('ACCOUNT.CONTENT_MODAL_DELETE') }}</p>
      </template>

      <template #modal-footer>
        <b-button variant="outline-secondary" @click="onClickCancelModalDelete()">{{
          $t('ACCOUNT.BUTTON_CANCEL')
        }}</b-button>

        <b-button variant="danger" @click="onClickSubmitModalDelete()">{{
          $t('ACCOUNT.BUTTON_DELETE')
        }}</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
const ACITON_ADD = 'ADD';
const ACTION_UPDATE = 'UPDATE';

export default {
  name: 'Account',
  data() {
    return {
      isUser: {
        fullname: '',
        code: '',
        telephone: '',
        email: '',
        role: null,
        blind: false,
        password: '',
      },

      isFilter: {
        fullname: '',
        role: null,
        phone: '',
        email: '',
      },

      listRole: [
        {
          value: null,
          text: 'Please select',
        },
        {
          value: 1,
          text: 'Teacher',
        },
        {
          value: 2,
          text: 'Student',
        },
      ],

      items: [
        {
          id: 1,
          student_id: 'GCH18154',
          fullname: 'Vũ Đức Việt',
          phone: '0984264170',
          email: 'vietvdgch18154@fpt.edu.vn',
          status: 1,
        },
        {
          id: 2,
          student_id: 'GCH18154',
          fullname: 'Vũ Đức Việt',
          phone: '0984264170',
          email: 'vietvdgch18154@fpt.edu.vn',
          status: 1,
        },
        {
          id: 3,
          student_id: 'GCH18154',
          fullname: 'Vũ Đức Việt',
          phone: '0984264170',
          email: 'vietvdgch18154@fpt.edu.vn',
          status: 0,
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
          key: 'student_id',
          label: this.$t('ACCOUNT.TABLE_TITLE_STUDENT_ID'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'fullname',
          label: this.$t('ACCOUNT.TABLE_TITLE_FULLNAME'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'phone',
          label: this.$t('ACCOUNT.TABLE_TITLE_PHONE'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'email',
          label: this.$t('ACCOUNT.TABLE_TITLE_EMAIL'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'status',
          label: this.$t('ACCOUNT.TABLE_TITLE_STATUS'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'actions',
          label: this.$t('ACCOUNT.TABLE_TITLE_ACTIONS'),
          thClass: 'base-th',
          tdClass: 'base-td',
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
	@import '@/scss/_variables';

	.account {
		padding: 10px;

		&__header {
			margin-bottom: 10px;

			.form-item {
				margin-top: 5px;
				margin-bottom: 5px;
			}
		}

		&__content {
			&__add {
				margin-bottom: 10px;

				button {
					min-width: 150px;

					i {
						margin-right: 10px;
					}
				}
			}

			margin-bottom: 10px;

			::v-deep table#table-account {
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

	.modal-account-content {
		.item-input {
			margin-bottom: 10px;
		}
	}

	.modal-account-footer {
	}
</style>
