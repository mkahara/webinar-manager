const { registerBlockType } = wp.blocks;
const { SelectControl } = wp.components;
const { withSelect } = wp.data;

const WebinarPromotionBlock = ({ attributes, setAttributes, webinars }) => {
    const selectedWebinar = attributes.selectedWebinar;

    const webinarOptions = webinars
        ? webinars.map((webinar) => ({
            label: webinar.title.rendered,
            value: webinar.id.toString(),
        }))
        : [];

    const fetchWebinarDetails = () => {
        console.log(ajaxurl);
        if (selectedWebinar) {
            jQuery.ajax({
                url: 'http://localhost:8080/webinar-manager/wp-json/webinar-manager/v1/get_webinar_details/',
                type: 'POST',
                data: {
                    action: 'render_webinar_promotion_block',
                    selectedWebinar: selectedWebinar
                },
                success: function(response) {
                    const blockElement = document.querySelector('.webinar-promotion-block');
                    blockElement.innerHTML = response;
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    };

    // Fetch details when component updates
    React.useEffect(() => {
        fetchWebinarDetails();
    }, [selectedWebinar]);

    return (
        wp.element.createElement('div', { className: 'webinar-promotion-block' },
            wp.element.createElement(SelectControl, {
                label: 'Select Webinar',
                options: webinarOptions,
                value: selectedWebinar,
                onChange: (selectedOption) => {
                    setAttributes({
                        selectedWebinar: selectedOption,
                    });
                },
            })
        )
    );
};

registerBlockType('webinar-promotion/webinar-promotion-block', {
    title: 'Webinar Promotion',
    description: "A block to promote webinars across the website.",
    icon: 'megaphone',
    category: 'common',
    attributes: {
        selectedWebinar: {
            type: 'string',
            default: '',
        },
    },
    supports: {
        html: false,
    },
    edit: withSelect((select) => {
        const webinars = select('core').getEntityRecords('postType', 'webinar');
        return {
            webinars,
        };
    })(WebinarPromotionBlock),

    save: () => {
        // Rendered on the frontend
        return null;
    },
});
