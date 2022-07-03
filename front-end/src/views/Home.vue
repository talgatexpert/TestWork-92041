<template>
	<v-data-table
			:headers="headers"
			:items="desserts"
			sort-by="calories"
			class="elevation-1"
			:server-items-length="paginationProps.total"
			@update:options="paginate"
			:loading="loading"

	>
		<template v-slot:top>
			<v-toolbar
					flat
			>
				<v-toolbar-title>Desserts</v-toolbar-title>
				<v-divider
						class="mx-4"
						inset
						vertical
				></v-divider>
				<v-spacer></v-spacer>
				<v-dialog
						v-model="dialog"
						max-width="500px"
				>
					<template v-slot:activator="{ on, attrs }">
						<v-btn
								color="#000"
								dark
								class="mb-2"
								@click="logout"
						>
							Logout
						</v-btn>

						<v-btn
								color="primary"
								dark
								class="mr-2 mb-2"
								v-bind="attrs"
								v-on="on"
						>
							New Item
						</v-btn>

					</template>
					<v-card>
						<v-card-title>
							<span class="text-h5">{{ formTitle }}</span>
						</v-card-title>

						<v-card-text>
							<v-container>
								<v-row>
									<v-col
											cols="12"
											sm="6"
											md="4"
									>
										<v-text-field
												v-model="editedItem.name"
												label="Dessert name"
												:error-messages="errors.name"
										></v-text-field>
									</v-col>
									<v-col
											cols="12"
											sm="6"
											md="4"
									>
										<v-text-field
												v-model="editedItem.calories"
												label="Calories"
												:error-messages="errors.calories"

										></v-text-field>
									</v-col>
									<v-col
											cols="12"
											sm="6"
											md="4"
									>
										<v-text-field
												v-model="editedItem.fat"
												label="Fat (g)"
												:error-messages="errors.fat"

										></v-text-field>
									</v-col>
									<v-col
											cols="12"
											sm="6"
											md="4"
									>
										<v-text-field
												v-model="editedItem.carbs"
												label="Carbs (g)"
												:error-messages="errors.carbs"

										></v-text-field>
									</v-col>
									<v-col
											cols="12"
											sm="6"
											md="4"
									>
										<v-text-field
												v-model="editedItem.protein"
												label="Protein (g)"
												:error-messages="errors.protein"

										></v-text-field>
									</v-col>
								</v-row>
							</v-container>
						</v-card-text>

						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn
									color="blue darken-1"
									text
									@click="close"
							>
								Cancel
							</v-btn>
							<v-btn
									color="blue darken-1"
									text
									@click="save"
							>
								Save
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
				<v-dialog v-model="dialogDelete" max-width="500px">
					<v-card>
						<v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
							<v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
							<v-spacer></v-spacer>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</v-toolbar>
		</template>
		<template v-slot:item.actions="{ item }">
			<v-icon
					small
					class="mr-2"
					@click="editItem(item)"
			>
				mdi-pencil
			</v-icon>
			<v-icon
					small
					@click="deleteItem(item)"
			>
				mdi-delete
			</v-icon>
		</template>
		<template v-slot:no-data>
			<v-btn
					color="primary"
					@click="initialize"
			>
				Reset
			</v-btn>
		</template>
	</v-data-table>
</template>
<script>
import request from "@/utils/request";
import {logout} from "@/api/auth";
import {removeToken} from "@/utils/auth";

export default {
	data: () => ({
		dialog: false,
		dialogDelete: false,
		loading: true,
		headers: [
			{text: 'Dessert ID', value: 'id',},
			{text: 'Dessert Name', value: 'name',},
			{text: 'Calories', value: 'calories'},
			{text: 'Fat (g)', value: 'fat'},
			{text: 'Carbs (g)', value: 'carbs'},
			{text: 'Protein (g)', value: 'protein'},
			{text: 'Actions', value: 'actions', sortable: false},
		],
		desserts: [],
		editedIndex: -1,
		editedItem: {
			id: null,
			name: '',
			calories: 0,
			fat: 0,
			carbs: 0,
			protein: 0,
		},
		defaultItem: {
			id: false,
			name: '',
			calories: 0,
			fat: 0,
			carbs: 0,
			protein: 0,
		},
		total: 0,
		paginationProps: {
			total: 0,
			last_page: null,
			next_page_url: null,
			prev_page_url: null,
		},
		errors: {}
	}),

	computed: {
		formTitle() {
			return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
		},
	},

	watch: {
		dialog(val) {
			val || this.close()
		},
		dialogDelete(val) {
			val || this.closeDelete()
		},
	},


	async mounted() {
		await this.initialize();
	},

	async beforeRouteUpdate(to, from, next) {
		await this.initialize(to);
		next();

	},

	methods: {

		async buildQuery(route = null) {
			const query = route ? route.query : this.$route.query;
			const page = query.page ?? 1;
			const sortBy = query.sort ?? "ASC";
			const orderBy = query.orderby ?? "id";
			let perPage = query.limit ?? 10;
			return '?limit=' + perPage + '&orderby=' + orderBy + '&page=' + page + '&sort=' + sortBy;
		},
		async initialize(route) {

			const query = await this.buildQuery(route);
			const response = await request('api/desserts' + query).catch(e => console.error(e)).finally(() => {
				this.loading = false;
			})
			if (response.success) {
				await this.$store.dispatch('dessert/get', response)
			}
			this.desserts = await this.$store.getters['dessert/desserts']
			this.paginationProps = await this.$store.getters['dessert/pagination']

		},

		async editItem(item) {
			this.editedIndex = this.desserts.indexOf(item)
			if (!item.id) {
				let response = await request('api/desserts/' + item.id).catch(e => console.error(e))
				this.editedItem = Object.assign({}, response.data);
			} else {
				this.editedItem = Object.assign({}, item);
			}
			this.dialog = true
		},

		deleteItem(item) {
			this.editedIndex = this.desserts.indexOf(item)
			this.editedItem = Object.assign({}, item)
			this.dialogDelete = true
		},

		async deleteItemConfirm() {
			this.clearError();
			await request.delete('api/desserts/' + this.editedItem.id)
					.then(result => {
						if (result.success) {
							this.desserts.splice(this.editedIndex, 1)
						}
					})
					.catch(e => console.error(e))
			this.closeDelete()
		},

		close() {
			this.dialog = false
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},

		closeDelete() {
			this.dialogDelete = false
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem)
				this.editedIndex = -1
			})
		},

		clearError() {
			this.errors = {}
		},
		async save() {
			this.clearError();
			if (this.editedIndex > -1) {
				await request.put('api/desserts/' + this.editedItem.id, this.editedItem)
						.then(result => {
							if (result.success) {
								Object.assign(this.desserts[this.editedIndex], result.data)
								this.close()
							}
						})
						.catch(({response: {data}}) => this.errors = data.errors)
			} else {
				await request.post('api/desserts/', this.editedItem)
						.then(result => {

							if (result.success) {
								this.desserts.push(result.data)
								this.close()
							}
						})
						.catch(({response: {data}}) => this.errors = data.errors)

			}
		},

		async paginate(val) {

			// emitted by the data-table when changing page, rows per page, or the sorted column/direction - will be also immediately emitted after the component was created
			const query = this.$route.query;
			const obj = Object.assign({}, query);
			obj.limit = val.itemsPerPage === -1 ? 'all' : val.itemsPerPage;
			if (val.descending) obj.desc = 'true';
			else delete obj.desc;
			obj.orderby = val?.sortBy[0];


			obj.page = val.page;
			if (val.sortDesc[0] === false) {
				obj.sort = "ASC"
			} else {
				obj.sort = "DESC"
			}


			// check if old and new query are the same - VueRouter will not change the route if they are, so probably this is the first page loading

			await this.$router.replace({...this.$router.currentRoute, query: obj}).catch((error) => {
				console.log('Page Error')
			});
		},

		async logout() {
			await logout();
			removeToken();
			await this.$store.dispatch('auth/logout');
		}
	},
}
</script>