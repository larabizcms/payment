import { getPaymentMethods } from "@admin/features/payment/method/methodActions";
import { showNotification } from "@admin/helpers";
import { useAppDispatch } from "@admin/hooks/hooks";
import { RootState } from "@local/store";
import { usePayOS } from "@payos/payos-checkout";
import { t } from "i18next";
import React, { useEffect } from "react";
import { useState } from "react";
import { useSelector } from "react-redux";

export type PaymentFormProps = {
    transaction: any,
    module: string,
    method: string,
    params?: any
};

export default function PaymentForm({ module, transaction, params }: PaymentFormProps) {
    const dispatch = useAppDispatch();
    const methods = useSelector((state: RootState) => state.payment.methods);

    const [payOSConfig, setPayOSConfig] = useState({
        RETURN_URL: window.location.origin + '/payment/balance/complete/' + transaction.id,
        ELEMENT_ID: "embedded-payment-container",
        CHECKOUT_URL: params.checkoutUrl,
        embedded: true,
        onSuccess: (event: any) => {
            showNotification(t('Payment successful'));
            setTimeout(() => {
                window.location.href = '/admin-cp/balance?success=true';
            }, 500);
        },
    });

    const { open, exit } = usePayOS(payOSConfig);

    useEffect(() => {
        if (methods === null) {
            dispatch(getPaymentMethods({ module: module }));
        }
    }, [methods, dispatch]);

    useEffect(() => {
        if (payOSConfig.CHECKOUT_URL != null) {
            console.log(payOSConfig);

            open();
        }
    }, [payOSConfig]);

    return (
        <>
            {t("Add funds")}: ${transaction.amount}

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
