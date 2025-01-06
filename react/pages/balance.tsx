import React from "react";
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
import { getMessageInError, showNotification } from "@admin/helpers";
import { getPaymentMethods } from "@admin/features/payment/method/methodActions";
import LoadingPage from "@admin/views/LoadingPage";

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

    React.useEffect(() => {
        dispatch(getPaymentMethods({ module: 'balance' }));
    }, [methods, dispatch]);

    const submitForm = (data: PaymenFormData) => {
        setLoading(true);
        dispatch(purchase({ module: 'balance', ...data }))
            .then((res) => {
                if (res.payload?.success) {
                    // console.log(res.payload.data.redirect_url);
                    window.location.href = res.payload.data.redirect_url;
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
                            // options={
                            //     {
                            //         paypal: t("PayPal / Visa / Mastercard"),
                            //         NganLuong: t("Momo / Bank transfer (VN)"),
                            //     }
                            // }
                            options={methods?.map((method) => ({ value: method.name, label: method.label }))}
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
            </Grid>
        </Grid>
    );
}
