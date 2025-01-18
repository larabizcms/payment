import React, { useState } from "react";
import { Page } from "@admin/features/page/pageSlice";
import { Grid, Icon } from "@mui/material";
import { selectAuthUser } from "@admin/features/selectors";
import { RootState, store } from "@local/store";
import { t } from "i18next";
import { LoadingButton } from "@mui/lab";
import Select from "@admin/components/forms/Select";
import Text from "@admin/components/forms/Text";
import { useForm } from "react-hook-form";
import { purchase } from "@admin/features/payment/payment/paymentActions";
import { useAppDispatch } from "@admin/hooks/hooks";
import { useSelector } from "react-redux";
import { convertToSelectOptions, getMessageInError, showNotification } from "@admin/helpers";
import { getPaymentMethods } from "@admin/features/payment/method/methodActions";
import MainCard from "@admin/layouts/components/MainCard";
import PaymentForm, { PaymentFormProps } from "./PaymentForm";

type Props = {
    page?: Page,
    uri: string,
};

type PaymenFormData = FormData & {
    amount: number,
    method: string,
};

export default function Balance({ page, uri }: Props) {
    const user = selectAuthUser(store.getState());
    const form = useForm<PaymenFormData>();
    const { handleSubmit } = form;
    const [loading, setLoading] = React.useState(false);
    const dispatch = useAppDispatch();
    const methods = useSelector((state: RootState) => state.payment.methods);
    const [paymentFormData, setPaymentFormData] = useState<PaymentFormProps>();

    React.useEffect(() => {
        if (methods === null) {
            dispatch(getPaymentMethods({ module: 'balance' }));
        }
    }, [methods, dispatch]);

    const openPayment = (module: string, method: string, transaction: any, params?: any) => {
        setPaymentFormData({ module, method, transaction, params });
    };

    const submitForm = (data: PaymenFormData) => {
        setLoading(true);
        dispatch(purchase({ module: 'balance', ...data }))
            .then((res) => {
                if (res.payload?.success) {
                    // console.log(res.payload.data.redirect_url);
                    if (res.payload.data.type === 'redirect') {
                        window.location.href = res.payload.data.redirect_url;
                    } else {
                        openPayment('balance', data.method, res.payload.data.transaction, res.payload.data.response);
                    }
                } else {
                    setLoading(false);
                    const error = getMessageInError(res.payload);
                    showNotification(error, 'error');
                }
            }).catch((error) => {
                showNotification(t('Something went wrong. Please try again...'), 'error');
                setLoading(false);
            });
    };

    return (
        <Grid container spacing={2}>
            <Grid item xs={12} md={9}>
                <h4>{t('Available balance: ${{balance}}', { balance: user?.balance || 0 })}</h4>

                <MainCard title={t('Purchase balance')}>
                    {paymentFormData && <PaymentForm {...paymentFormData}  />}

                    {/* <iframe src="https://pay.payos.vn/embedded/1894f0ec95d44961899dad5c9d087412/success/" style={{ width: 300, height: 300 }} /> */}

                    {!paymentFormData && (
                        <form noValidate onSubmit={handleSubmit(submitForm)}>
                            <Grid container spacing={3}>
                                <Text
                                    label={t("Amount")}
                                    name="amount"
                                    form={form as any}
                                    config={{ rules: ['required'] }}
                                    type="number"
                                />

                                <Select
                                    label={t("Payment Method")}
                                    name="method"
                                    form={form as any}
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
                        </form>
                    )}
                </MainCard>
            </Grid>
        </Grid>
    );
}
