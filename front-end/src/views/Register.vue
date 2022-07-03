<template>

	<v-card width="500" class="mx-auto mt-15 my-auto">
		<v-alert
				transition="scroll-x-reverse-transition"
				dense
				:value="alert"
				prominent
				type="success"
		>Congratulations you are registered.</v-alert>
		<v-form ref="form"
		        class="py-10 px-15 w-100">
			<v-text-field
					v-model="form.name"
					label="Name"
					required
					:rules="rules.name"
					:error-messages="errors.name"
			></v-text-field>
			<v-text-field
					v-model="form.email"
					label="Email"
					required
					:rules="rules.email"
					:error-messages="errors.email"
			></v-text-field>
			<v-text-field
					v-model="form.password"
					type="password"
					label="Password"
					:rules="rules.password"
					:error-messages="errors.password"
					required
			></v-text-field>
			<div class="d-flex align-center justify-lg-space-between">
					<v-btn to="/" depressed plain class="pl-0 text-decoration-underline" text>Already have account</v-btn>
				<v-btn :disabled="processing" outlined @click="login">Login</v-btn>
			</div>
		</v-form>
	</v-card>
</template>

<script>


import {sanctum} from "@/api/request";
import {login, register} from "@/api/auth";
import {setToken} from "@/utils/auth";

export default {
	name: 'Login',
	data() {
		return {
			valid: true,
			rules: {
				email: [
					v => !!v || 'E-mail is required',
					v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
				],
				name: [
					v => !!v || 'Name is required',
				],
				password: [
					v => !!v || 'Password is required',
					v => (v && v.length <= 10) || 'Password must be less than 10 characters',
				],
			},
			form: {
				name: '',
				email: '',
				password: '',
			},
			processing: false,
			alert: false,
			errors: {}
		}
	},
	methods: {

		async login() {
			this.processing = true
			this.errors = {}
			await sanctum()
			await register(this.form).then(async result => {
				this.alert = true

				setTimeout(() => {
					this.$router.push('/')
				}, 1500)
			}).catch(({response: {data}}) => {
				this.errors = data?.errors ?? {}
			}).finally(() => {
				this.processing = false
			})
		},
	}
};
</script>
