<template>
	<div class="card">
		<div class="card-header font-weight-600">
			تعديل الحساب
		</div>
		<div class="card-body">
			<div class="form-group">
				<label class="font-weight-600">
					الاسم الكامل
					<small class="text-danger">*</small>
				</label>
				<input class="form-control" :class="{'is-invalid': errors.name}" type="text"
				       v-model="user.name" name="name" @keypress.enter="updateAccount" required>
				<div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
			</div>
			<div class="form-group">
				<label class="font-weight-600">
					البريد الالكتروني
					<small class="text-danger">*</small>
				</label>
				<input class="form-control" :class="{'is-invalid': errors.email}" type="email"
				       v-model="user.email" name="email" @keypress.enter="updateAccount" required>
				<div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
			</div>
			<hr>

			<div>
				<label class="mb-0">
					<input type="checkbox" v-model="user.settings.email.newsletter">
					<span class="font-weight-600">النشرة البريدية</span>
					-
					أخبار وتحديثات منصة لينكاتي، اعدك لن تكون مزعجة!
				</label>
			</div>

		</div>
		<div class="card-footer text-left">
			<button class="btn btn-secondary" type="button" :disabled="submiting" @click="updateAccount">
				<i class="fas fa-spinner fa-spin" v-if="submiting"></i>
				حفظ البيانات
			</button>
		</div>
	</div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        computed: mapState(['user']),
        data() {
            return {
                errors: {},
                submiting: false,
            }
        },
        methods: {
            updateAccount() {
                this.submiting = true;
                axios.patch('/account', this.user)
                    .then(response => {
                        this.errors = {};
                        this.submiting = false;
                        this.$toasted.success(response.data.message);
                        this.$store.commit('setUser', response.data.data);
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        this.submiting = false;
                    });
            }
        }
    }
</script>