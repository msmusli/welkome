<template>
    <div>
        <div class="row mb-4">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="form-group">
                    <select name="hotel" id="hotel" class="form-control" v-model="hotel" @change="updateServiceList">
                        <option v-for="hotel in hotels" :key="hotel.hash" :value="hotel.hash">
                            {{ hotel.business_name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" v-model="query" :placeholder='$t("common.search")' aria-label="Search" required>
                    <div class="input-group-append">
                        <button class="input-group-text" id="btnGroupAddon">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="crud-list">
            <div class="crud-list-heading mt-2">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <h5>{{ $t('common.description') }}</h5>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <h5>{{ $t('common.price') }}</h5>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <h5>{{ $t('common.status') }}</h5>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                        <h5>{{ $t('common.options') }}</h5>
                    </div>
                </div>
            </div>
            <div class="crud-list-items" v-if="services.length != 0">
                <div class="crud-list-row" v-for="service in services" :key="service.hash">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-self-center">
                        <p>
                            <a :href="'/services/' + service.hash">
                                {{ service.description }}
                            </a>
                        </p>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 align-self-center">
                        <p>
                            <a :href="'/services/' + service.hash">
                                {{ new Intl.NumberFormat("de-DE").format(service.price) }}
                            </a>
                        </p>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 align-self-center">
                        <p class="text-primary"><i class="fas" :class="service.status == '1' ? 'fa-check' : 'fa-times-circle'"></i></p>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2 align-self-center">
                        <div class="dropdown">
                            <button type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>

                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu dropdown-menu-right">
                                <a v-if="$can('services.edit')" :href="'/services/' + service.hash + '/edit'" class="dropdown-item">{{ $t('common.edit') }}</a>
                                <a v-if="$can('services.edit')" :href="'/services/' + service.hash + '/toggle'" class="dropdown-item">
                                    {{ service.status == 1 ? $t('common.disable') : $t('common.disable') }}
                                </a>
                                <a v-if="$can('services.destroy')" href="#" :data-url="'/services/' + service.hash" data-method="DELETE" id="modal-confirm" onclick="confirmAction(this, event)" class="dropdown-item">
                                    {{ $t('common.delete.item') }}</a>
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="crud-list-items" v-else>
                <div class="crud-list-row">
                    <div class="card mt-4">
                        <div class="card-body">
                            {{ $t('common.noRecords') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        if (this.hotels.length > 0) {
            this.hotel = _.first(this.hotels).hash
            this.services = _.first(this.hotels).services
        }
    },
    props: ['hotels'],
    data() {
        return {
            hotel: null,
            services: [],
            query: ''
        }
    },
    methods: {
        updateServiceList() {
            _.map(this.hotels, (headquarters) => {
                if (headquarters.hash == this.hotel) {
                    this.services = headquarters.services
                    this.query = ''
                }
            })
        }
    },
    watch: {
        query: function(current, old) {
            if (current.length == 0 || this.query.length == 0) {
                this.updateServiceList()
            } else {
                if (current.length >= 3) {
                    axios.post('/services/search', {
                        query: this.query,
                        hotel: this.hotel
                    }).then(response => {
                        let services = JSON.parse(response.data.services);

                        if (services.length > 0) {
                            this.services = services
                        } else {
                            this.services = []

                            toastr.info(
                                this.$root.$t('common.without.results'),
                                this.$root.$t('common.sorry')
                            );
                        }
                    }).catch(e => {
                        toastr.error(
                            this.$root.$t('common.try'),
                            'Error'
                        );
                    });
                }
            }
        }
    },
};
</script>