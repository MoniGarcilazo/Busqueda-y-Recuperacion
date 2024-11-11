import { ChangeEvent, useState } from 'react';

import { IconField } from 'primereact/iconfield';
import { InputIcon } from 'primereact/inputicon';
import { InputText } from 'primereact/inputtext';

import '../styles/Finder.css';

function Finder() {
    const [input, setInput] = useState<string>('');

    const handleInputChange = (
        event: ChangeEvent<HTMLInputElement>
    ) => {
        setInput(event.target.value);
    }

    const reset = () => {
        console.log("Hola mundo...");
        setInput('');
    }

    return (
        <section className='flex gab-3 finder'>
            <form id="finder" action="" method='GET' className='field'>
                <IconField iconPosition='left'>
                    <InputIcon className='pi pi-search'></InputIcon>
                    <InputText
                        placeholder="Search"
                        className='field input-text'
                        value={input}
                        onChange={handleInputChange}
                    />
                </IconField>
                {input !== '' && (
                    <InputIcon
                    className='pi pi-times reset-icon'
                    onClick={reset}></InputIcon>
                )}
            </form>
        </section>
    );
}

export default Finder;