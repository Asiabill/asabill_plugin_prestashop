<style>

    /*#asiabill_from{*/
        /*padding: 10px;*/
        /*border-radius: 5px;*/
        /*background: #fff;*/
    /*}*/
    /*.from_group{*/
        /*margin: 20px 0;*/
    /*}*/
    /*.nobootstrap input[type="text"],.nobootstrap select{*/
        /*background: #F5F8F9;*/
        /*border: 1px solid #C7D6DB;*/
        /*border-radius: 5px;*/
        /*padding: 3px 5px;*/
        /*width: 70%;*/
    /*}*/

    /*.from_header{*/
        /*border-bottom: 1px solid #ccc;*/
    /*}*/
    /*.from_footer{*/
        /*border-top: 1px solid #ccc;*/
        /*text-align: right;*/
        /*padding-top: 10px;*/
    /*}*/


</style>

<form id="module_form" action="{$action_url}" class="defaultForm form-horizontal" method="post" novalidate >
    <div id="asiabill_from" class="panel">
        <input type="hidden" name="submitAsiabill" value="1">
        <div class="panel-heading">
            <p>Set Information</p>
        </div>
        <div class="form-wrapper">

            <div class="form-group">
                <label class="control-label col-lg-3">Mode :</label>
                <div class="col-lg-9">
                    <select name="mode">
                        <option value="0" {if $mode=='0'}selected{/if} >Test</option>
                        <option value="1" {if $mode=='1'}selected{/if} >Live</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 required">Mer No :</label>
                <div class="col-lg-9">
                    <input  type="text" name="merNo" class="" value="{$merNo}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">Gateway No :</label>
                <div class="col-lg-9">
                    <input type="text" name="gatewayNo" class="" value="{$gatewayNo}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">Sign Key :</label>
                <div class="col-lg-9">
                    <input type="text" name="signkey" class="" value="{$signkey}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 required">Test Mer No :</label>
                <div class="col-lg-9">
                    <input  type="text" name="test_merNo" class="" value="{$test_merNo}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">Test Gateway No :</label>
                <div class="col-lg-9">
                    <input type="text" name="test_gatewayNo" class="" value="{$test_gatewayNo}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">Test Sign Key :</label>
                <div class="col-lg-9">
                    <input type="text" name="test_signkey" class="" value="{$test_signkey}">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-lg-3">Order State :</label>
                <div class="col-lg-9">
                    <select name="order_state">
                        {foreach from=$order_states item=item }
                            <option value="{$item['id_order_state']}" {if $item['id_order_state']==$order_state}selected{/if} >{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">Succeed State :</label>
                <div class="col-lg-9">
                    <select name="succeed_state">
                        {foreach from=$order_states item=item }
                            <option value="{$item['id_order_state']}" {if $item['id_order_state']==$succeed_state}selected{/if} >{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">Fail State :</label>
                <div class="col-lg-9">
                    <select name="fail_state">
                        {foreach from=$order_states item=item }
                            <option value="{$item['id_order_state']}" {if $item['id_order_state']==$fail_state}selected{/if} >{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">Wait State :</label>
                <div class="col-lg-9">
                    <select name="wait_state">
                        {foreach from=$order_states item=item }
                            <option value="{$item['id_order_state']}" {if $item['id_order_state']==$wait_state}selected{/if} >{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">Display :</label>
                <div class="col-lg-9">
                    <select name="display">
                        <option value="IFRAME">iFrame</option>
                        <option value="REDIRECT" {if $display=='REDIRECT'}selected{/if} >Redirect</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">iframe height(px) :</label>
                <div class="col-lg-9">
                    <input type="text" name="iframe_height" class="" value="{$iframe_height }">
                </div>
            </div>
        </div>


        <div class="panel-footer">
            <button type="submit" value="1" id="module_form_submit_btn" name="btnSubmit" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> SAVE
            </button>
        </div>
    </div>
</form>
