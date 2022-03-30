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
								placeholder="Enter fullname"
							></b-form-input>
						</div>
					</b-col>

					<b-col cols="12" sm="12" md="6" lg="6" xl="3">
						<div class="form-item">
							<label for="filter-role">Role</label>
							<b-form-select
								id="filter-role"
								v-model="isFilter.role"
								:options="listRole"
							/>
						</div>
					</b-col>

					<b-col cols="12" sm="12" md="6" lg="6" xl="3">
						<div class="form-item">
							<label for="filter-phone">Telephone</label>
							<b-form-input
								id="filter-phone"
								v-model="isFilter.phone"
								placeholder="Enter phone"
							></b-form-input>
						</div>
					</b-col>

					<b-col cols="12" sm="12" md="6" lg="6" xl="3">
						<div class="form-item">
							<label for="filter-email">Email</label>
							<b-form-input
								id="filter-email"
								v-model="isFilter.email"
								placeholder="Enter email"
							></b-form-input>
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
							<b-button variant="primary" size="sm">
								<i class="fas fa-plus-circle"></i>
								<span>Add</span>
							</b-button>
						</div>
					</b-col>
				</b-row>

				<b-row>
					<b-col>
						<b-table
							bordered
							striped
							responsive
							:no-sort-reset="true"
							no-local-sorting
							show-empty
							id="table-account"
							:fields="fields"
							:items="items"
							:per-page="pagination.perPage"
							:current-page="pagination.page"
						>
							<template #cell(status)="data">
								<b-badge v-if="data.item.status === 1" href="#" variant="success"
									>Active</b-badge
								>
								<b-badge v-if="data.item.status === 0" href="#" variant="danger"
									>Inactive</b-badge
								>
							</template>

							<template #cell(actions)="data">
								<div class="td-actions">
									<div class="actions-edit">
										<b-button variant="warning" size="sm">
											<i class="fas fa-edit"></i>
										</b-button>
									</div>

									<div class="actions-delete">
										<b-button variant="danger" size="sm">
											<i class="fas fa-trash"></i>
										</b-button>
									</div>
								</div>
							</template>

							<template #empty="">
								<span class="d-flex justify-content-center">No data</span>
							</template>
						</b-table>
					</b-col>
				</b-row>

				<b-row>
					<b-col>
						<b-pagination
							pills
							size="sm"
							first-number
							last-number
							align="right"
							v-model="pagination.page"
							:total-rows="pagination.total"
							:per-page="pagination.perPage"
							aria-controls="table-account"
						/>
					</b-col>
				</b-row>
			</b-card>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'Account',
		data() {
			return {
				isFilter: {
					fullname: '',
					role: null,
					phone: '',
					email: ''
				},

				listRole: [
					{
						value: null,
						text: 'Please select'
					},
					{
						value: 1,
						text: 'Teacher'
					},
					{
						value: 2,
						text: 'Student'
					}
				],

				items: [
					{
						id: 1,
						student_id: 'GCH18154',
						fullname: 'Vũ Đức Việt',
						phone: '0984264170',
						email: 'vietvdgch18154@fpt.edu.vn',
						status: 1
					},
					{
						id: 2,
						student_id: 'GCH18154',
						fullname: 'Vũ Đức Việt',
						phone: '0984264170',
						email: 'vietvdgch18154@fpt.edu.vn',
						status: 1
					},
					{
						id: 3,
						student_id: 'GCH18154',
						fullname: 'Vũ Đức Việt',
						phone: '0984264170',
						email: 'vietvdgch18154@fpt.edu.vn',
						status: 0
					}
				],

				pagination: {
					page: 1,
					perPage: 20,
					total: 1
				}
			};
		},
		computed: {
			fields() {
				return [
					{
						key: 'student_id',
						label: 'Student ID',
						sortable: true,
						thClass: 'base-th',
						tdClass: 'base-td'
					},
					{
						key: 'fullname',
						label: 'Fullname',
						sortable: true,
						thClass: 'base-th',
						tdClass: 'base-td'
					},
					{
						key: 'phone',
						label: 'Phone',
						sortable: true,
						thClass: 'base-th',
						tdClass: 'base-td'
					},
					{
						key: 'email',
						label: 'Email',
						sortable: true,
						thClass: 'base-th',
						tdClass: 'base-td'
					},
					{
						key: 'status',
						label: 'Status',
						sortable: true,
						thClass: 'base-th',
						tdClass: 'base-td'
					},
					{
						key: 'actions',
						label: 'Actions',
						thClass: 'base-th',
						tdClass: 'base-td'
					}
				];
			}
		}
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
					min-width: 110px;

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
</style>
