{capture name=path}
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='asiabill'}">{l s='Checkout' mod='asiabill'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Credit Card' mod='asiabill'}
{/capture}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
    <p class="alert alert-warning">{l s='Your shopping cart is empty.' mod='asiabill'}</p>
{else}

    <div id="loading" style="text-align: center" >
        <span>Loading...Please do not refresh the page.</span>
    </div>

    <form name="checkout_creditcard" id="checkout_creditcard" action="{$parameter['handler']}" method="post">
        {foreach from=$parameter key=key item=item }
            {if $key!='handler'}
            <input type="hidden" name="{$key}" value='{$item}'>
            {/if}
        {/foreach}
    </form>

    <iframe style="display: none" width="100%" height="{$iframe_height}px" scrolling="auto" name="ifrm_creditcard_checkout" id="ifrm_creditcard_checkout" style="border:none; margin: 0 auto; overflow:auto;"></iframe>


    {if $pay_display=='IFRAME'}
        <script type="text/javascript">
            document.checkout_creditcard.target = "ifrm_creditcard_checkout";
            document.checkout_creditcard.submit();

            var ifrm_cc  = document.getElementById("ifrm_creditcard_checkout");
            var loading  = document.getElementById("loading");

            ifrm_cc.onload = function(){
                loading.style.display = 'none';
                ifrm_cc.style.display = '';
            }
        </script>
    {else}
        <script type="text/javascript">
            document.checkout_creditcard.submit();
        </script>
    {/if}

{/if}


