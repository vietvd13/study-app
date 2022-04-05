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

    <div class="account">
      <div class="account__header">
        <b-card>
          <b-row>
            <b-col cols="12" sm="12" md="6" lg="6" xl="3">
              <div class="form-item">
                <label for="filter-fullname">
                  {{ $t('ACCOUNT.LABEL_FILTER_FULLNAME') }}
                </label>
                <b-form-input
                  id="filter-fullname"
                  v-model="isFilter.fullname"
                  :placeholder="$t('ACCOUNT.PLACEHOLDER_FILTER_FULLNAME')"
                />
              </div>
            </b-col>

            <b-col cols="12" sm="12" md="6" lg="6" xl="3">
              <div class="form-item">
                <label for="filter-role">
                  {{ $t('ACCOUNT.LABEL_FILTER_ROLE') }}
                </label>
                <b-form-select
                  id="filter-role"
                  v-model="isFilter.role"
                >
                  <b-form-select-option :value="null">
                    {{ $t('ROLE.PLEASE_SELECT') }}
                  </b-form-select-option>
                  <b-form-select-option
                    v-for="role in listRole"
                    :key="role.text"
                    :value="role.value"
                  >
                    {{ $t(role.text) }}
                  </b-form-select-option>
                </b-form-select>
              </div>
            </b-col>

            <b-col cols="12" sm="12" md="6" lg="6" xl="3">
              <div class="form-item">
                <label for="filter-phone">
                  {{ $t('ACCOUNT.LABEL_FILTER_TELEPHONE') }}
                </label>
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
                  <span class="d-flex justify-content-center">
                    {{ $t('ACCOUNT.TABLE_CONTENT_NO_DATA') }}
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
            <b-form-input
              id="form-fullname"
              v-model="isUser.fullname"
              :placeholder="$t('ACCOUNT.PLACEHOLDER_FORM_FULLNAME')"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <label for="form-code">{{ $t('ACCOUNT.LABEL_FORM_CODE') }}</label>
            <b-form-input
              id="form-code"
              v-model="isUser.code"
              :placeholder="$t('ACCOUNT.PLACEHOLDER_FORM_CODE')"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <label for="form-telephone">{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}</label>
            <b-form-input
              id="form-telephone"
              v-model="isUser.telephone"
              :placeholder="$t('ACCOUNT.PLACEHOLDER_FORM_TELEPHONE')"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <label for="form-email">{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}</label>
            <b-form-input
              id="form-email"
              v-model="isUser.email"
              :placeholder="$t('ACCOUNT.PLACEHOLDER_FORM_EMAIL')"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <label for="form-role">{{ $t('ACCOUNT.LABEL_FORM_ROLE') }}</label>
            <b-form-select id="form-role" v-model="isUser.role" :disabled="isProcess">
              <b-form-select-option :value="null" :disabled="true">
                {{ $t('ROLE.PLEASE_SELECT') }}
              </b-form-select-option>
              <b-form-select-option
                v-for="role in listRole"
                :key="role.text"
                :value="role.value"
              >
                {{ $t(role.text) }}
              </b-form-select-option>
            </b-form-select>
          </div>

          <div class="item-input">
            <label for="form-password">{{ $t('ACCOUNT.LABEL_FORM_PASSWORD') }}</label>
            <b-form-input
              id="form-password"
              v-model="isUser.password"
              type="password"
              :placeholder="$t('ACCOUNT.PLACEHOLDER_FORM_PASSWORD')"
              autocomplete="new-password"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <label for="form-status">{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}</label>
            <b-form-select id="form-status" v-model="isUser.status" :disabled="isProcess">
              <b-form-select-option :value="null" :disabled="true">
                {{ $t('ACCOUNT_STATUS.PLEASE_SELECT') }}
              </b-form-select-option>

              <b-form-select-option
                v-for="status in listStatus"
                :key="status.text"
                :value="status.value"
              >
                {{ $t(status.text) }}
              </b-form-select-option>
            </b-form-select>
          </div>

          <div class="item-input">
            <b-form-checkbox id="form-blind" v-model="isUser.blind" name="blind" :disabled="isProcess">
              {{ $t('ACCOUNT.LABEL_FORM_BLIND') }}
            </b-form-checkbox>
          </div>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCancelModalForm()">
            {{ $t('ACCOUNT.BUTTON_CANCEL') }}
          </b-button>
          <b-button class="btn-custom-green" :disabled="isProcess" @click="onClickSumbitModalForm()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('ACCOUNT.BUTTON_SUBMIT') }}
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
          <h5>{{ $t('ACCOUNT.MODAL_TITLE_DELETE') }}</h5>
        </template>

        <template #default>
          <p>{{ $t('ACCOUNT.CONTENT_MODAL_DELETE') }}</p>
        </template>

        <template #modal-footer>
          <b-button class="btn-custom-outline-charade" @click="onClickCancelModalDelete()">
            {{ $t('ACCOUNT.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" @click="onClickSubmitModalDelete()">
            {{ $t('ACCOUNT.BUTTON_DELETE') }}
          </b-button>
        </template>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
const ACITON_ADD = 'ADD';
const ACTION_UPDATE = 'UPDATE';

import CONST_ROLE from '@/const/role';
import CONST_ACCOUNT_STATUS from '@/const/account_status';

const URL_API = {
  getAllAccount: '/users',
  getOneAccount: '/users',
  addAccount: '/users',
  updateAccount: '/users',
  deleteAccount: '/users',
};
import {
  getAllAccount,
  getOneAccount,
  postAccount,
  putAccount,
  deleteAccount,
} from '@/api/modules/account';

import { MakeToast } from '../../toast/toastMessage';

export default {
  name: 'Account',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      isUser: {
        fullname: '',
        code: '',
        telephone: '',
        email: '',
        role: null,
        blind: false,
        status: null,
        password: '',
      },

      isFilter: {
        fullname: '',
        role: null,
        phone: '',
        email: '',
      },

      listRole: CONST_ROLE.LIST_ROLE,
      listStatus: CONST_ACCOUNT_STATUS.ACCOUNT_STATUS,

      items: [],

      pagination: {
        page: 1,
        perPage: 20,
        total: 1,
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
          key: 'user_code',
          label: this.$t('ACCOUNT.TABLE_TITLE_STUDENT_ID'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'name',
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
  created() {
    this.initData();
  },
  methods: {
    async initData() {
      this.overlay.show = true;

      await this.handleGetAllAccount();

      this.overlay.show = false;
    },
    async handleGetAllAccount() {
      const URL = URL_API.getAllAccount;
      const PARAMS = {
        page: this.pagination.page,
        per_page: this.pagination.perPage,
      };

      try {
        const res = await getAllAccount(URL, PARAMS);

        if (res['status'] === 200) {
          this.items = res['data']['data'];

          this.pagination.page = res['data']['current_page'];
          this.pagination.total = res['data']['total'];
        }
      } catch (err) {
        console.log(err);
      }
    },
    async handleGetOneAccount(id) {
      const URL = `${URL_API.getOneAccount}/${id}`;

      try {
        const res = await getOneAccount(URL);

        if (res['status'] === 200) {
          this.setDataModalForm(res['data']);
        }
      } catch (err) {
        console.log(err);
      }
    },
    async handleAddAccount() {
      const URL = URL_API.addAccount;
      const DATA = {
        name: this.isUser.fullname,
        password: this.isUser.password,
        user_code: this.isUser.code,
        email: this.isUser.email,
        phone: this.isUser.telephone,
        status: this.isUser.status,
        isBlind: this.isUser.blind ? 1 : 0,
        role: this.isUser.role,
      };

      try {
        const res = await postAccount(URL, DATA);

        if (res['status'] === 200) {
          this.notifyAddSuccess(res.data);
          this.initData();
        }
      } catch (err) {
        console.log(err);
      }
    },
    async handleUpdateAccount(id) {
      const URL = `${URL_API.updateAccount}/${id}`;

      const DATA = {
        name: this.isUser.fullname,
        user_code: this.isUser.code,
        email: this.isUser.email,
        phone: this.isUser.telephone,
        status: this.isUser.status,
        isBlind: this.isUser.blind ? 1 : 0,
        role: this.isUser.role,
      };

      if (this.isUser.password) {
        DATA['new_password'] = this.isUser.password;
      }

      try {
        const res = await putAccount(URL, DATA);

        if (res['status'] === 200) {
          this.notifyUpdateSuccess(res.data);
          this.initData();
        }
      } catch (err) {
        console.log(err);
      }
    },
    async handleDeleteAccount(id) {
      const URL = `${URL_API.deleteAccount}/${id}`;

      try {
        const res = await deleteAccount(URL);

        if (res['status'] === 200) {
          this.notifyDeleteSuccess();
          this.initData();
        }
      } catch (err) {
        console.log(err);
      }
    },
    notifyAddSuccess(user) {
      MakeToast({
        variant: 'success',
        title: this.$t('TOAST.SUCCESS'),
        content: this.$t('ACCOUNT.NOTIFY.ADD_SUCCESS', { email: user.email }),
      });
    },
    notifyAddFail() {
      MakeToast({
        variant: 'danger',
        title: this.$t('TOAST.DANGER'),
        content: this.$t('ACCOUNT.NOTIFY.ADD_FAILD'),
      });
    },
    notifyUpdateSuccess(user) {
      MakeToast({
        variant: 'success',
        title: this.$t('TOAST.SUCCESS'),
        content: this.$t('ACCOUNT.NOTIFY.UPDATE_SUCCESS', { email: user.email }),
      });
    },
    notifyDeleteSuccess() {
      MakeToast({
        variant: 'success',
        title: this.$t('TOAST.SUCCESS'),
        content: this.$t('ACCOUNT.NOTIFY.DELETE_SUCCESS'),
      });
    },
    showModalForm() {
      this.visibleModalForm = true;
    },
    hideModalForm() {
      this.visibleModalForm = false;
      this.resetDataModalFrom();
    },
    resetDataModalFrom() {
      const DEFAULT = {
        fullname: '',
        code: '',
        telephone: '',
        email: '',
        role: null,
        blind: false,
        status: null,
        password: '',
      };

      this.isUser = DEFAULT;
    },
    setDataModalForm(user) {
      const DATA = {
        fullname: user['name'],
        code: user['user_code'],
        telephone: user['phone'],
        role: user['roles'][0]['id'],
        blind: user['isBlind'] === 1,
        status: user['status'],
        password: '',
      };

      this.isUser = DATA;
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
      await this.handleGetOneAccount(id);
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
      this.isProcess = true;
      if (this.isAction === ACITON_ADD) {
        await this.handleAddAccount();
      }

      if (this.isAction === ACTION_UPDATE) {
        await this.handleUpdateAccount(this.idHandle);
      }
      this.isProcess = false;
      this.hideModalForm();
    },
    onClickCancelModalDelete() {
      this.hidenModalDelete();
    },
    async onClickSubmitModalDelete() {
      await this.handleDeleteAccount(this.idHandle);
      this.hidenModalDelete();
    },
  },
};
</script>

<style lang="scss" scoped>
	@import '@/scss/_variables';

	.account {
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

    .icon-loading {
        font-size: 50px;
        color: $forest-green;
    }
</style>
