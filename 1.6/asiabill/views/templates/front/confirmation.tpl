{capture name=path}
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='asiabill'}">{l s='Checkout' mod='asiabill'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Credit Card' mod='asiabill'}
{/capture}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}


{if $state == 1}
    <p class="alert alert-success">
        {l s='Your order on' mod='asiabill'} {$shop_name} {l s='is now complete.' mod='asiabill'}
    </p>

{else}
    <p class="alert alert-danger">
        {l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='asiabill'}
    </p>

{/if}


<p class="cart_navigation exclusive">
    <a class="button-exclusive btn btn-default" href="{$link->getModuleLink('','order-history')}" title="{l s='Check your order historical' mod='asiabill'}">
        <i class="icon-chevron-left"></i>{l s='Check your order historical' mod='asiabill'}
    </a>
</p>


