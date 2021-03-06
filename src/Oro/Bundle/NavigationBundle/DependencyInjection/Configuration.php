<?php

namespace Oro\Bundle\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;
use Oro\Bundle\NavigationBundle\Config\Definition\Builder\MenuTreeBuilder;

class Configuration implements ConfigurationInterface
{
    const ROOT_NODE = 'oro_menu_config';
    const NAVIGATION_ELEMENTS_NODE = 'oro_navigation_elements';


    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::ROOT_NODE, 'array', new MenuTreeBuilder());

        $node = $rootNode->children();
        $this->setChildren($node);
        $node->end();

        SettingsBuilder::append(
            $rootNode,
            [
                'maxItems'        => ['value' => 20],
                'title_suffix'    => ['value' => ''],
                'title_delimiter' => ['value' => '-'],
                'breadcrumb_menu' => ['value' => 'application_menu'],
            ]
        );

        return $treeBuilder;
    }

    /**
     * Add children nodes to menu
     *
     * @param $node NodeBuilder
     * @return Configuration
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function setChildren($node)
    {
        $node->
            arrayNode(self::NAVIGATION_ELEMENTS_NODE)
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->children()
                    ->booleanNode('default')->defaultFalse()->end()
                    ->arrayNode('routes')
                        ->useAttributeAsKey('routes')
                            ->prototype('boolean')
                                ->treatNullLike(false)
                                ->defaultFalse()
                            ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ->arrayNode('templates')
            ->useAttributeAsKey('templates')
            ->prototype('array')
                ->children()
                    ->scalarNode('template')->end()
                    ->scalarNode('clear_matcher')->end()
                    ->scalarNode('depth')->end()
                    ->scalarNode('allow_safe_labels')->end()
                    ->scalarNode('currentAsLink')->end()
                    ->scalarNode('currentClass')->end()
                    ->scalarNode('ancestorClass')->end()
                    ->scalarNode('firstClass')->end()
                    ->scalarNode('lastClass')->end()
                    ->scalarNode('compressed')->end()
                    ->scalarNode('block')->end()
                    ->scalarNode('rootClass')->end()
                    ->scalarNode('isDropdown')->end()
                ->end()
            ->end()
        ->end()
        ->arrayNode('items')
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->children()
                    ->scalarNode('id')->end()
                    ->scalarNode('name')->end()
                    ->scalarNode('label')->end()
                    ->scalarNode('uri')->end()
                    ->scalarNode('route')->end()
                    ->scalarNode('read_only')->end()
                    ->scalarNode('aclResourceId')->end()
                    ->scalarNode('translateDomain')->end()
                    ->arrayNode('translateParameters')
                        ->useAttributeAsKey('translateParameters')->prototype('scalar')->end()
                    ->end()
                    ->booleanNode('translateDisabled')->end()
                    ->arrayNode('attributes')
                        ->children()
                            ->scalarNode('class')->end()
                            ->scalarNode('id')->end()
                        ->end()
                    ->end()
                    ->arrayNode('linkAttributes')
                        ->children()
                            ->scalarNode('class')->end()
                            ->scalarNode('id')->end()
                            ->scalarNode('target')->end()
                            ->scalarNode('title')->end()
                            ->scalarNode('rel')->end()
                            ->scalarNode('type')->end()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                        ->end()
                    ->end()
                    ->arrayNode('childrenAttributes')
                        ->children()
                            ->scalarNode('class')->end()
                            ->scalarNode('id')->end()
                        ->end()
                    ->end()
                    ->arrayNode('labelAttributes')
                        ->children()
                            ->scalarNode('class')->end()
                            ->scalarNode('id')->end()
                        ->end()
                    ->end()
                    ->scalarNode('display')->end()
                    ->scalarNode('displayChildren')->end()
                    ->scalarNode('type')->end()
                    ->arrayNode('routeParameters')
                        ->useAttributeAsKey('routeParameters')->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('extras')
                        ->useAttributeAsKey('extras')->prototype('variable')->end()
                    ->end()
                    ->booleanNode('showNonAuthorized')->end()
                ->end()
            ->end()
        ->end()
        ->arrayNode('tree')
            ->useAttributeAsKey('id')
                ->prototype('array')
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('area')->end()
                        ->scalarNode('read_only')->end()
                        ->scalarNode('max_nesting_level')->end()
                        ->arrayNode('extras')
                            ->useAttributeAsKey('extras')->prototype('scalar')->end()
                        ->end()
                        ->menuNode('children')->menuNodeHierarchy()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $this;
    }
}
