const HIGHT = 80
const MEDIUM = 60

const getColor = (value) => {
    if(value >= HIGHT) return 'green'
    if(value >= MEDIUM) return 'orange'
    if(value >= 0) return 'red'
    return 'gray'
}

export default getColor