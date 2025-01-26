import { showNotification } from "@admin/helpers";
import { usePayOS } from "@payos/payos-checkout";
import { t } from "i18next";
import React, { useEffect } from "react";
import { useState } from "react";

export type PaymentFormProps = {
    transaction: any,
    module: string,
    method: string,
    params?: any
};

export default function PaymentForm({ module, transaction, params }: PaymentFormProps) {
    const [payOSConfig, setPayOSConfig] = useState({
        RETURN_URL: window.location.origin + `/payment/${module}/complete/${transaction.id}`,
        ELEMENT_ID: "embedded-payment-container",
        CHECKOUT_URL: params.checkoutUrl,
        embedded: true,
        onSuccess: (event: any) => {
            showNotification(t('Payment successful'));
            setTimeout(() => {
                window.location.reload();
            }, 500);
        },
    });

    const { open, exit } = usePayOS(payOSConfig);

    useEffect(() => {
        if (payOSConfig.CHECKOUT_URL != null) {
            open();
        }
    }, [payOSConfig]);

    return (
        <>
            {t("Total Amount")}: ${transaction.amount}

            {/* <Select
                label={t("Payment Method")}
                name="method"
                disabled={methods?.length === 0}
                options={methods ? convertToSelectOptions(methods, 'label', 'name') : undefined}
                defaultValue={method}
            /> */}

            <div
                id="embedded-payment-container"
                style={{
                    height: "350px",
                }}
            ></div>

            {/* <br />

            <Grid item xs={12}>
                <LoadingButton
                    loading={loading}
                    variant="contained"
                    color="primary"
                    type="submit"
                    startIcon={<Icon>add</Icon>}
                >{t('Pay Now')}</LoadingButton>
            </Grid> */}
        </>
    );
}
