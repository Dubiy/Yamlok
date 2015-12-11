<?php

namespace Dubiy\YamlokBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class YamlType extends AbstractType
{
    protected $data = [];

    /**
     * DeviceType constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->data as $key => $value) {
            $builder->add($key, 'text', [
                'mapped' => null
            ]);
        }
        $builder->add('sumbit', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Dubiy\YamlokBundle\Model\Yaml'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'yaml';
    }
}
