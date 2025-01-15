import { getPaymentMethods } from "@admin/features/payment/method/methodActions";
import { convertToSelectOptions, showNotification } from "@admin/helpers";
import { useAppDispatch } from "@admin/hooks/hooks";
import { RootState } from "@local/store";
import { LoadingButton } from "@mui/lab";
import { Grid, Icon, Select, TextField } from "@mui/material";
import { usePayOS } from "@payos/payos-checkout";
import { t } from "i18next";
import React from "react";
import { useState } from "react";
import { useSelector } from "react-redux";

export type PaymentFormProps = {
    module: string,
};

export default function PaymentForm({ module }: PaymentFormProps) {
    const [isOpen, setIsOpen] = useState(false);
    const [message, setMessage] = useState("");
    const [isCreatingLink, setIsCreatingLink] = useState(false);
    const dispatch = useAppDispatch();
    const methods = useSelector((state: RootState) => state.payment.methods);
    const [loading, setLoading] = React.useState(false);

    const [payOSConfig, setPayOSConfig] = useState({
        RETURN_URL: window.location.origin,
        ELEMENT_ID: "embedded-payment-container",
        CHECKOUT_URL: null,
        embedded: true,
        onSuccess: (event: any) => {
            showNotification(t('Payment successful'), 'success');
        },
    });
    const { open, exit } = usePayOS(payOSConfig as any);

    React.useEffect(() => {
        if (methods === null) {
            dispatch(getPaymentMethods({ module: module }));
        }
    }, [methods, dispatch]);

    return (
        <Grid container spacing={3}>
            <TextField
                label={t("Amount")}
                name="amount"
                disabled
                type="number"
            />

            <Select
                label={t("Payment Method")}
                name="method"
                disabled={methods?.length === 0}
                options={methods ? convertToSelectOptions(methods, 'label', 'name') : undefined}
                config={{ rules: ['required'] }}
                defaultValue={'paypal'}
            />

            <br />

            <Grid item xs={12}>
                <LoadingButton
                    loading={loading}
                    variant="contained"
                    color="primary"
                    type="submit"
                    startIcon={<Icon>add</Icon>}
                >{t('Add funds')}</LoadingButton>
            </Grid>
        </Grid>
    );
}
