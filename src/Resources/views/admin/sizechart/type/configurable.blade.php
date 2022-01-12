<add-custom-options></add-custom-options>

@push('scripts')

<script type="text/x-template" id="add-custom-options-template">
   
    <div class="control-group" :class="[errors.has('config_option') ? 'has-error' : '']" v-if="! showCustomOptions">
        <label for="config_option" class="required">{{ __('sizechart::app.sizechart.template.config-option') }}</label>
        <select v-validate="'required'" v-model="attribute" class="control"  id="select_attribute" @change="selectAttribute($event)" name="select_attribute" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.select-attribute') }}&quot;">
            <option value="">{{ __('sizechart::app.sizechart.template.select-attribute') }}</option>
            
            @foreach ($attributes as $attribute)
                @if($attribute->name != 'Price')
                    <option value="{{ $attribute->id }}">
                        {{ $attribute->name ? $attribute->name : $attribute->admin_name }}
                    </option>
                @endif
            @endforeach
            
        </select>
        <span class="control-error" v-if="errors.has('config_option')">@{{ errors.first('config_option') }}</span>
    </div>

    <div class="control-group" v-else>
        <div>
            <button type="button" class="btn btn-lg btn-primary" @click="backtoinput()">
                {{ __('sizechart::app.sizechart.template.back') }}
            </button>
            <button type="button" class="btn btn-lg btn-primary" @click="addCustomRow()">
                {{ __('sizechart::app.sizechart.template.add-row') }}
            </button>
        </div>
        <div class="customOptionDiv">
            <div class="customOption">
                <span class="customSpan">
                    <input type="text" class="custom_input"  v-validate="{ required: true }" id="config_option" name="formname[0][label]" placeholder="Enter Option Name"/>
                </span>
                <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                    <input type="text" class="custom_input" id="config_option" :value="inputOption" :name="'formname[0]['+ inputOption +']'" readonly/>
                </span>
            </div>
            <div class="customOption" v-for='(addRow, key) in addRows'>
                <span class="customSpan">
                    <input type="text" class="custom_input_t" v-validate="{ required: true }" id="config_option" :name="'formname['+ addRow.row +'][name]'"/>
                </span>
                <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                    <input type="text" class="custom_input_t" v-validate="{ required: true }" id="config_option" value="" :name="'formname['+ addRow.row +']['+ inputOption +']'"/>
                </span>
                <span>
                    <i class="icon remove-icon" @click="removeCustomRow(key)"></i>
                </span>
            </div>
            <input type="hidden" name="template_type" value="configurable"/>
            <input type="hidden" v-model="configAttribute" name="config_attribute" value="0"/>
        </div>
    </div>
</script>

<script>
        Vue.component('add-custom-options', {

            template: '#add-custom-options-template',

            data: function() {
                return {
                    customOptionValues: '',
                    showCustomOptions: false,
                    inputOptions: '',
                    counter: 0,
                    configAttribute: '',
                    attribute: '',
                    addRows: []
                }
            },

            methods: {
                selectAttribute: function(event) {
                    this.configAttribute = event.target.value;

                    this.$http.get(`attribute?attribute-id=` + event.target.value)
                        .then(response => {
                            if (response.data.status){
                                this.customOptionValues = response.data.customOptionValues;
                                this.addCustomOption();
                            }else{
                                window.flashMessages = [{
                                    'type': 'alert-error',
                                    'message': "{{ __('sizechart::app.sizechart.template.custom-option-not-available') }}"
                                }];

                                this.$root.addFlashMessages()
                            }
                        })
                        .catch(error => {
                            window.flashMessages = [{
                                'type': 'alert-error',
                                'message': "{{ __('error.something_went_wrong') }}"
                            }];

                            this.$root.addFlashMessages()
                        })
                },

                addCustomOption: function() {
                    if (this.customOptionValues != '' || this.customOptionValue != null) {
                        this.showCustomOptions = true;
                        this.inputOptions = this.customOptionValues.split(",");    
                    } else {
                        window.flashMessages = [{
                            'type': 'alert-error',
                            'message': "{{ __('sizechart::app.sizechart.template.empty-custom-option') }}"
                        }];

                        this.$root.addFlashMessages()
                    }
                    
                },

                backtoinput: function() {
                    this.showCustomOptions = false;
                    this.counter = 0;
                    this.addRows = [];
                },

                addCustomRow: function() {
                    this.counter += 1;
                    this.addRows.push({
                        row: this.counter
                    });
                },

                removeCustomRow: function(key) {
                    this.addRows.splice(key, 1)
                }
            }
        });
</script>
    
@endpush