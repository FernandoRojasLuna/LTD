export const bannerValidationRules = {
    title: [
        { required: true, message: 'El título es requerido' },
        { max: 255, message: 'El título no puede exceder 255 caracteres' }
    ],
    subtitle: [
        { max: 255, message: 'El subtítulo no puede exceder 255 caracteres' }
    ],
    image: [
        { required: true, message: 'La imagen es requerida' },
        { type: 'url', message: 'Debe ser una URL válida' }
    ],
    link: [
        { type: 'url', message: 'Debe ser una URL válida' }
    ],
    order: [
        { type: 'number', min: 0, message: 'El orden debe ser mayor o igual a 0' }
    ]
}

export const contentValidationRules = {
    title: [
        { required: true, message: 'El título es requerido' },
        { max: 255, message: 'El título no puede exceder 255 caracteres' }
    ],
    content: [
        { required: true, message: 'El contenido es requerido' }
    ],
    type: [
        { required: true, message: 'El tipo es requerido' }
    ],
    image: [
        { type: 'url', message: 'Debe ser una URL válida' }
    ]
}

export const projectValidationRules = {
    name: [
        { required: true, message: 'El nombre es requerido' },
        { max: 255, message: 'El nombre no puede exceder 255 caracteres' }
    ],
    description: [
        { required: true, message: 'La descripción es requerida' }
    ],
    image: [
        { required: true, message: 'La imagen es requerida' },
        { type: 'url', message: 'Debe ser una URL válida' }
    ],
    status: [
        { required: true, message: 'El estado es requerido' }
    ],
    start_date: [
        { required: true, message: 'La fecha de inicio es requerida' },
        { type: 'date', message: 'Debe ser una fecha válida' }
    ],
    end_date: [
        { type: 'date', message: 'Debe ser una fecha válida' }
    ],
    url: [
        { type: 'url', message: 'Debe ser una URL válida' }
    ],
    repository: [
        { type: 'url', message: 'Debe ser una URL válida' }
    ]
}

export const technologyValidationRules = {
    name: [
        { required: true, message: 'El nombre es requerido' },
        { max: 255, message: 'El nombre no puede exceder 255 caracteres' }
    ],
    icon: [
        { max: 255, message: 'El ícono no puede exceder 255 caracteres' }
    ],
    color: [
        { pattern: /^#[0-9A-F]{6}$/i, message: 'Debe ser un color hexadecimal válido (ej: #FF0000)' }
    ]
}

export function validateField(value, rules) {
    for (const rule of rules) {
        if (rule.required && (!value || value.toString().trim() === '')) {
            return rule.message
        }
        
        if (value && rule.max && value.length > rule.max) {
            return rule.message
        }
        
        if (value && rule.min && value < rule.min) {
            return rule.message
        }
        
        if (value && rule.type === 'url') {
            const urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/
            if (!urlPattern.test(value)) {
                return rule.message
            }
        }
        
        if (value && rule.type === 'date') {
            if (isNaN(Date.parse(value))) {
                return rule.message
            }
        }
        
        if (value && rule.pattern && !rule.pattern.test(value)) {
            return rule.message
        }
    }
    
    return null
}

export function validateForm(formData, validationRules) {
    const errors = {}
    
    for (const [field, rules] of Object.entries(validationRules)) {
        const error = validateField(formData[field], rules)
        if (error) {
            errors[field] = error
        }
    }
    
    return {
        isValid: Object.keys(errors).length === 0,
        errors
    }
}
