export const handleGetPaymentLinkPayos = async () => {
    setIsCreatingLink(true);
    exit();
    const response = await fetch(
        "http://localhost:3030/create-embedded-payment-link",
        {
            method: "POST",
        }
    );
    if (!response.ok) {
        console.log("Server doesn't response");
    }

    const result = await response.json();
    setPayOSConfig((oldConfig) => ({
        ...oldConfig,
        CHECKOUT_URL: result.checkoutUrl,
    }));

    setIsOpen(true);
    setIsCreatingLink(false);
};
