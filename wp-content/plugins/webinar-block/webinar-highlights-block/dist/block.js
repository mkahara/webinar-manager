//const { registerBlockType } = wp.blocks;

wp.blocks.registerBlockType('webinar-highlight/webinar-highlight', {
    title: 'Webinar Highlight Block',
    description: 'This block adds webinar highlights with time and title.',
    category: 'common',
    attributes: {
        highlight_time: {
            type: 'string',
            default: '',
        },
        highlight_title: {
            type: 'string',
            default: '',
        },
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { highlight_time, highlight_title } = attributes;

        const onChangeHighlightTime = (newValue) => {
            setAttributes({ highlight_time: newValue });
        };

        const onChangeHighlightTitle = (newValue) => {
            setAttributes({ highlight_title: newValue });
        };

        return [
            wp.element.createElement(
                'div',
                { key: 'time', className: 'highlight-time' },
                wp.element.createElement('input', {
                    type: 'text',
                    value: highlight_time,
                    onChange: (e) => onChangeHighlightTime(e.target.value),
                })
            ),
            wp.element.createElement(
                'div',
                { key: 'title', className: 'highlight-title' },
                wp.element.createElement('input', {
                    type: 'text',
                    value: highlight_title,
                    onChange: (e) => onChangeHighlightTitle(e.target.value),
                })
            )
        ];
    },
    save: function(props) {
        const { attributes } = props;
        const { highlight_time, highlight_title } = attributes;

        return wp.element.createElement(
            'div',
            { className: 'webinar-highlight' },
            wp.element.createElement('span', { className: 'highlight-time' }, highlight_time),
            wp.element.createElement('span', { className: 'highlight-title' }, highlight_title)
        );
    },
});
