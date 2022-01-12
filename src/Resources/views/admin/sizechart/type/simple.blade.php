<add-custom-options></add-custom-options>

@push('scripts')

<script type="text/x-template" id="add-custom-options-template">
    <div class="control-group" :class="[errors.has('config_option') ? 'has-error' : '']" v-if="! showCustomOptions">
        <label for="config_option" class="required">{{ __('sizechart::app.sizechart.template.config-option') }}</label>
        <input type="text" v-model="customOptionValues" v-validate="{ required: true }" class="control" id="config_option" name="config_option" value="{{ request()->input('config_option') ?: old('config_option') }}" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.config-option') }}&quot;"/>
        <span class="control-error" v-if="errors.has('config_option')">@{{ errors.first('config_option') }}</span>
        <span class="control-info mt-10">{{ __('sizechart::app.sizechart.template.config-option-info') }}</span>
        <br>
        <button type="button" class="btn btn-lg btn-primary" @click="addCustomOption()">
            {{ __('sizechart::app.sizechart.template.continue') }}
        </button>
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
                    <input type="text" class="custom_input"  v-validate="{ required: true }"  name="formname[0][label]" placeholder="Enter Option Name"/>
                </span>
                <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                    <input type="text" class="custom_input"  :value="inputOption" :name="'formname[0]['+ inputOption +']'" readonly/>
                </span>
            </div>
            <div class="customOption" v-for='(addRow, key) in addRows'>
                <span class="customSpan">
                    <input type="text" class="custom_input_t" v-validate="{ required: true }"  :name="'formname['+ addRow.row +'][name]'"/>
                </span>
                <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                    <input type="text" class="custom_input_t" v-validate="{ required: true }" value="" :name="'formname['+ addRow.row +']['+ inputOption +']'"/>
                </span>
                <span>
                    <i class="icon remove-icon" @click="removeCustomRow(key)"></i>
                </span>
            </div>
            <input type="hidden" name="template_type" value="simple"/>
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
                    addRows: []
                }
            },

            methods: {
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